<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Category;
use App\Services\ProductImportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('importProducts')
                ->label('Import Products')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Import Products from CSV / Excel')
                ->modalSubmitActionLabel('Import')
                ->schema([
                    FileUpload::make('file')
                        ->label('CSV or Excel file')
                        ->disk('local')
                        ->directory('product-imports')
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'application/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/octet-stream',
                        ])
                        ->maxSize(10240)
                        ->required()
                        ->columnSpanFull()
                        ->helperText('Accepted: .csv, .xlsx or .xls. Use a header row with column names such as name, price, quantity, category, engine, transmission, gvw, store, ecm_miles, youtube_url, extra_description and image_url.'),
                    Toggle::make('has_header')
                        ->label('First row contains column headers')
                        ->default(true),
                    Select::make('default_category')
                        ->label('Default category')
                        ->options(fn (): array => Category::query()
                            ->orderBy('name')
                            ->pluck('name', 'name')
                            ->all())
                        ->searchable()
                        ->preload()
                        ->placeholder('None — rows without a category will fail')
                        ->helperText('Used for any row whose Category column is empty.'),
                ])
                ->action(function (array $data): void {
                    $this->runProductImport($data);
                }),
        ];
    }

    protected function runProductImport(array $data): void
    {
        $relativePath = $data['file'] ?? null;

        if (is_array($relativePath)) {
            $relativePath = $relativePath[0] ?? null;
        }

        if (blank($relativePath)) {
            Notification::make()
                ->title('No file uploaded')
                ->danger()
                ->send();

            return;
        }

        try {
            $result = app(ProductImportService::class, [
                'options' => [
                    'default_category' => $data['default_category'] ?? null,
                ],
            ])->import(
                Storage::disk('local')->path($relativePath),
                (bool) ($data['has_header'] ?? true),
            );
        } catch (\InvalidArgumentException $exception) {
            Notification::make()
                ->title('Import failed')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        } finally {
            Storage::disk('local')->delete($relativePath);
        }

        $summary = sprintf(
            '%d created, %d updated, %d failed of %d rows.',
            $result->createdRows,
            $result->updatedRows,
            $result->failedRows,
            $result->rowsProcessed,
        );

        if ($result->failedRows > 0) {
            $details = collect($result->failures)
                ->take(5)
                ->map(fn (array $failure): string => 'Row '.$failure['row'].': '.implode('; ', $failure['errors']))
                ->implode('<br>');

            Notification::make()
                ->title('Import completed with errors')
                ->body($summary.'<br><br>'.$details)
                ->warning()
                ->persistent()
                ->send();

            return;
        }

        Notification::make()
            ->title('Import completed')
            ->body($summary)
            ->success()
            ->send();
    }
}
