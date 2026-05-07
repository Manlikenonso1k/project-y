<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->disabled(),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])
                    ->columns(2),
                
                Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')->disabled(),
                        Forms\Components\TextInput::make('last_name')->disabled(),
                        Forms\Components\TextInput::make('email')->disabled(),
                        Forms\Components\TextInput::make('phone')->disabled(),
                    ])
                    ->columns(2),
                
                Section::make('Shipping Address')
                    ->schema([
                        Forms\Components\TextInput::make('address')->disabled(),
                        Forms\Components\TextInput::make('city')->disabled(),
                        Forms\Components\TextInput::make('state')->disabled(),
                        Forms\Components\TextInput::make('postal_code')->disabled(),
                        Forms\Components\TextInput::make('country')->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer'),
                
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn ($state): string => '$' . number_format((float) $state, 2))
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
