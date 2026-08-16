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
}
