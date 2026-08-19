<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique('products', 'slug', ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('item_number')
                    ->label('Item / Stock #')
                    ->maxLength(255)
                    ->helperText('Unique identifier from the import file. Used to skip already-imported rows.'),

                Section::make('Specs (auto-extracted from import)')
                    ->schema([
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\TextInput::make('manufacturer')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subcategory')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('mileage')
                            ->numeric()
                            ->nullable()
                            ->suffix('mi'),

                        Forms\Components\TextInput::make('horsepower')
                            ->numeric()
                            ->nullable()
                            ->suffix('HP'),

                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Link to the original listing / source.'),
                    ])
                    ->columns(3),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(5),

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),

                Section::make('Product Type')
                    ->schema([
                        Forms\Components\Toggle::make('is_variable')
                            ->label('Variable Product (Weight-based pricing)')
                            ->default(false)
                            ->reactive(),
                    ]),

                Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->prefix('$')
                            ->visible(fn (Get $get) => ! $get('is_variable')),

                        Forms\Components\TextInput::make('original_price')
                            ->numeric()
                            ->step(0.01)
                            ->nullable()
                            ->prefix('$')
                            ->visible(fn (Get $get) => ! $get('is_variable')),
                    ])
                    ->columns(2),

                Section::make('Weight-Based Variants')
                    ->schema([
                        Forms\Components\Repeater::make('variants')
                            ->relationship('variants')
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->required()
                                    ->step(0.01)
                                    ->label('Weight'),

                                Forms\Components\Select::make('unit')
                                    ->options([
                                        'g' => 'Grams (g)',
                                        'kg' => 'Kilograms (kg)',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->step(0.01)
                                    ->prefix('$')
                                    ->label('Price'),

                                Forms\Components\TextInput::make('stock')
                                    ->numeric()
                                    ->required()
                                    ->default(0)
                                    ->label('Stock'),
                            ])
                            ->columns(4)
                            ->collapsible()
                            ->visible(fn (Get $get) => $get('is_variable')),
                    ]),

                Section::make('Inventory & Media')
                    ->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->label('Stock Quantity')
                            ->visible(fn (Get $get) => ! $get('is_variable')),

                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->multiple()
                            ->appendFiles()
                            ->reorderable()
                            ->panelLayout('grid')
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->maxFiles(8)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120)
                            ->previewable(true)
                            ->openable()
                            ->downloadable()
                            ->label('Product Images')
                            ->helperText('Upload one or more product images (PNG, JPG, JPEG, WEBP - Max 5MB each)')
                            ->afterStateUpdated(function (Get $get, Set $set, ?array $state): void {
                                $paths = array_values(array_filter(array_map(
                                    fn ($value): ?string => is_string($value)
                                        ? Product::normalizeImagePath($value)
                                        : (is_array($value) ? Product::normalizeImagePath($value['path'] ?? $value['file'] ?? $value['url'] ?? null) : null),
                                    $state ?? [],
                                )));
                                $primaryImage = $get('primary_image');

                                if (filled($primaryImage) && in_array($primaryImage, $paths, true)) {
                                    return;
                                }

                                $set('primary_image', $paths[0] ?? null);
                            }),

                        Forms\Components\FileUpload::make('primary_image')
                            ->image()
                            ->label('Primary Image')
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->previewable(true)
                            ->openable()
                            ->downloadable()
                            ->dehydrateStateUsing(fn ($state): ?string => is_string($state) ? Product::normalizeImagePath($state) : (is_array($state) ? Product::normalizeImagePath($state['path'] ?? $state['file'] ?? $state['url'] ?? $state['name'] ?? null) : null)),

                        Forms\Components\TextInput::make('image_url')
                            ->label('External Image URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com/photo.jpg'),
                    ])
                    ->columns(2),

                Section::make('Vehicle & Listing Details')
                    ->schema([
                        Forms\Components\TextInput::make('engine')
                            ->label('Engine')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('transmission')
                            ->label('Transmission')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('gvw')
                            ->label('GVW')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('store')
                            ->label('Store')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('ecm_miles')
                            ->label('Ecm Miles')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('extra_description')
                            ->label('Extra Description')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Product')
                            ->default(false),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primary_image_url')
                    ->label('Image')
                    ->getStateUsing(fn (Product $record): ?string => $record->primary_image_url)
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('item_number')
                    ->label('Item')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('manufacturer')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('mileage')
                    ->formatStateUsing(fn ($state): ?string => $state === null ? null : number_format((int) $state))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('horsepower')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),


                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn ($state): string => '$'.number_format((float) $state, 2))
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('engine')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('gvw')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('store')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ecm_miles')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_active'),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Year')
                    ->multiple()
                    ->options(fn (): array => Product::query()
                        ->whereNotNull('year')
                        ->selectRaw('year, count(*) as total')
                        ->groupBy('year')
                        ->orderByDesc('year')
                        ->get()
                        ->mapWithKeys(fn ($row): array => [$row->year => $row->year.' ('.$row->total.')'])
                        ->all())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['values'] ?? null)
                        ? $query->whereIn('year', $data['values'])
                        : $query),

                Tables\Filters\SelectFilter::make('manufacturer')
                    ->label('Manufacturer')
                    ->multiple()
                    ->options(fn (): array => Product::query()
                        ->whereNotNull('manufacturer')
                        ->selectRaw('manufacturer, count(*) as total')
                        ->groupBy('manufacturer')
                        ->orderBy('manufacturer')
                        ->get()
                        ->mapWithKeys(fn ($row): array => [$row->manufacturer => $row->manufacturer.' ('.$row->total.')'])
                        ->all())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['values'] ?? null)
                        ? $query->whereIn('manufacturer', $data['values'])
                        : $query),

                Tables\Filters\SelectFilter::make('mileage')
                    ->label('Mileage')
                    ->multiple()
                    ->options(static::mileageFilterOptions())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['values'] ?? null)
                        ? $query->where(function (Builder $subQuery) use ($data): void {
                            foreach ($data['values'] as $value) {
                                $subQuery->orWhere(function (Builder $rangeQuery) use ($value): void {
                                    if ($value === 'na') {
                                        $rangeQuery->whereNull('mileage');

                                        return;
                                    }

                                    $rangeQuery->whereBetween('mileage', explode('-', $value));
                                });
                            }
                        })
                        : $query),

                Tables\Filters\SelectFilter::make('horsepower')
                    ->label('Horsepower')
                    ->multiple()
                    ->options(static::horsepowerFilterOptions())
                    ->query(fn (Builder $query, array $data): Builder => filled($data['values'] ?? null)
                        ? $query->where(function (Builder $subQuery) use ($data): void {
                            foreach ($data['values'] as $value) {
                                $subQuery->orWhere(function (Builder $rangeQuery) use ($value): void {
                                    if ($value === 'na') {
                                        $rangeQuery->whereNull('horsepower');

                                        return;
                                    }

                                    $rangeQuery->whereBetween('horsepower', explode('-', $value));
                                });
                            }
                        })
                        : $query),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<string, string> mileage range key => label (with count)
     */
    protected static function mileageFilterOptions(): array
    {
        return Cache::remember('product-filter-mileage-options', 120, function () {
            $ranges = [
                'na' => 'N/A',
                '0-99999' => '0–99,999',
                '100000-199999' => '100,000–199,999',
                '200000-299999' => '200,000–299,999',
                '300000-399999' => '300,000–399,999',
                '400000-499999' => '400,000–499,999',
                '500000-599999' => '500,000–599,999',
                '600000-699999' => '600,000–699,999',
                '700000-799999' => '700,000–799,999',
                '800000-899999' => '800,000–899,999',
                '900000-999999' => '900,000–999,999',
            ];

            $counts = Product::query()
                ->selectRaw('SUM(CASE WHEN mileage IS NULL THEN 1 ELSE 0 END) as na')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 0 AND 99999 THEN 1 ELSE 0 END) as range_0_99999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 100000 AND 199999 THEN 1 ELSE 0 END) as range_100000_199999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 200000 AND 299999 THEN 1 ELSE 0 END) as range_200000_299999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 300000 AND 399999 THEN 1 ELSE 0 END) as range_300000_399999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 400000 AND 499999 THEN 1 ELSE 0 END) as range_400000_499999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 500000 AND 599999 THEN 1 ELSE 0 END) as range_500000_599999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 600000 AND 699999 THEN 1 ELSE 0 END) as range_600000_699999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 700000 AND 799999 THEN 1 ELSE 0 END) as range_700000_799999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 800000 AND 899999 THEN 1 ELSE 0 END) as range_800000_899999')
                ->selectRaw('SUM(CASE WHEN mileage BETWEEN 900000 AND 999999 THEN 1 ELSE 0 END) as range_900000_999999')
                ->first();

            $options = [];

            foreach ($ranges as $key => $label) {
                $column = $key === 'na' ? 'na' : 'range_'.str_replace('-', '_', $key);
                $count = (int) ($counts?->{$column} ?? 0);
                $options[$key] = $label.' ('.$count.')';
            }

            return $options;
        });
    }

    /**
     * @return array<string, string> horsepower range key => label (with count)
     */
    protected static function horsepowerFilterOptions(): array
    {
        return Cache::remember('product-filter-horsepower-options', 120, function () {
            $ranges = [
                'na' => 'N/A',
                '250-299' => '250–299',
                '300-349' => '300–349',
                '350-399' => '350–399',
                '400-449' => '400–449',
                '450-499' => '450–499',
                '500-999' => '500+',
            ];

            $counts = Product::query()
                ->selectRaw('SUM(CASE WHEN horsepower IS NULL THEN 1 ELSE 0 END) as na')
                ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 250 AND 299 THEN 1 ELSE 0 END) as range_250_299')
                ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 300 AND 349 THEN 1 ELSE 0 END) as range_300_349')
                ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 350 AND 399 THEN 1 ELSE 0 END) as range_350_399')
                ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 400 AND 449 THEN 1 ELSE 0 END) as range_400_449')
                ->selectRaw('SUM(CASE WHEN horsepower BETWEEN 450 AND 499 THEN 1 ELSE 0 END) as range_450_499')
                ->selectRaw('SUM(CASE WHEN horsepower >= 500 THEN 1 ELSE 0 END) as range_500_999')
                ->first();

            $options = [];

            foreach ($ranges as $key => $label) {
                $column = $key === 'na' ? 'na' : 'range_'.str_replace('-', '_', $key);
                $count = (int) ($counts?->{$column} ?? 0);
                $options[$key] = $label.' ('.$count.')';
            }

            return $options;
        });
    }
}
