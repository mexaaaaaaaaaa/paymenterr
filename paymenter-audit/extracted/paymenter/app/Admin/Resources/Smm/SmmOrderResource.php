<?php

namespace App\Admin\Resources\Smm;

use App\Admin\Resources\Smm\Pages\ListSmmOrders;
use App\Admin\Resources\Smm\Pages\ViewSmmOrder;
use Paymenter\Extensions\Others\Smm\Models\SmmOrder;
use Paymenter\Extensions\Others\Smm\Services\OrderService;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class SmmOrderResource extends Resource
{
    protected static ?string $model = SmmOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'ri-file-list-line';

    protected static string|\UnitEnum|null $navigationGroup = 'SMM Hub';

    protected static ?int $navigationSort = 2;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_code')->label('Order ID')->searchable(),
                TextColumn::make('user.name')->label('Customer')->searchable(),
                TextColumn::make('items.service.name')->label('Service'),
                TextColumn::make('status')->badge(),
                TextColumn::make('provider_order_id')->label('Provider ID'),
                TextColumn::make('amount')->money('INR'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('sync')
                    ->visible(fn ($record) => $record->provider_order_id && ! $record->isTerminal())
                    ->action(fn ($record) => (new OrderService())->syncOrderStatus($record))
                    ->successNotification(Notification::make()->title('Synced')),
                Action::make('retry')
                    ->visible(fn ($record) => $record->status === SmmOrder::STATUS_FAILED)
                    ->action(fn ($record) => (new OrderService())->submitToProvider($record))
                    ->successNotification(Notification::make()->title('Resubmitted')),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSmmOrders::route('/'),
            'view' => ViewSmmOrder::route('/{record}'),
        ];
    }
}
