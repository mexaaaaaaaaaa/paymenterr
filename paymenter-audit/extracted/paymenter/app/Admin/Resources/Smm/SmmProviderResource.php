<?php

namespace App\Admin\Resources\Smm;

use App\Admin\Resources\Smm\Pages\ListSmmProviders;
use App\Admin\Resources\Smm\Pages\ViewSmmProvider;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;
use Paymenter\Extensions\Others\Smm\Services\EasySmmpanelApi;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Number;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;

class SmmProviderResource extends Resource
{
    protected static ?string $model = SmmProvider::class;

    protected static string|\BackedEnum|null $navigationIcon = 'ri-global-line';

    protected static string|\UnitEnum|null $navigationGroup = 'SMM Hub';

    protected static ?int $navigationSort = 3;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('code')->required(),
                TextInput::make('api_endpoint')->required()->url(),
                TextInput::make('api_key')->password()->revealable()->encrypted(),
                Checkbox::make('is_active'),
                Checkbox::make('is_default'),
                Number::make('balance')->step('0.00000001')->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('code'),
                ToggleColumn::make('is_active'),
                ToggleColumn::make('is_default'),
                TextColumn::make('balance'),
                TextColumn::make('last_balance_check_at')->dateTime(),
            ])
            ->filters([])
            ->recordActions([
                Action::make('test_connection')
                    ->action(fn ($record) => app(EasySmmpanelApi::class, ['provider' => $record])->testConnection())
                    ->successNotification(fn () => Notification::make()->title('Connection OK')),
                Action::make('check_balance')
                    ->action(fn ($record) => app(EasySmmpanelApi::class, ['provider' => $record])->balance())
                    ->successNotification(Notification::make()->title('Balance checked')),
                Action::make('sync_services')
                    ->action(fn () => dispatch(new \Paymenter\Extensions\Others\Smm\Jobs\SyncServicesJob()))
                    ->successNotification(Notification::make()->title('Sync queued')),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSmmProviders::route('/'),
            'view' => ViewSmmProvider::route('/{record}'),
        ];
    }
}
