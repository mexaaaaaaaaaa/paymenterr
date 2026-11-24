<?php

namespace App\Admin\Resources\Smm;

use App\Admin\Resources\Smm\Pages\ListSmmServices;
use App\Admin\Resources\Smm\Pages\CreateSmmService;
use App\Admin\Resources\Smm\Pages\EditSmmService;
use App\Admin\Resources\Smm\Pages\ViewSmmService;
use Paymenter\Extensions\Others\Smm\Models\SmmService;
use Paymenter\Extensions\Others\Smm\Services\PricingEngine;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Number;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SmmServiceResource extends Resource
{
    protected static ?string $model = SmmService::class;

    protected static string|\BackedEnum|null $navigationIcon = 'ri-shopping-bag-line';

    protected static string|\UnitEnum|null $navigationGroup = 'SMM Hub';

    protected static ?int $navigationSort = 1;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                Select::make('provider_id')->relationship('provider', 'name')->required()->label('Provider'),
                Select::make('category_id')->relationship('category', 'name')->label('Category'),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('slug')->required()->maxLength(255),
                TextInput::make('provider_service_id')->label('Provider Service ID')->maxLength(255),
                Textarea::make('description')->maxLength(1000),
                TextInput::make('type')->label('Type'),
                TextInput::make('channel')->label('Channel / Platform'),
                Number::make('min_quantity')->label('Min Quantity'),
                Number::make('max_quantity')->label('Max Quantity'),
                Number::make('provider_cost')->label('Provider Cost')->step('0.00000001'),
                TextInput::make('provider_currency')->label('Provider Currency')->maxLength(3),
                Number::make('markup_percent')->label('Markup %')->step('0.0001'),
                Number::make('fixed_markup')->label('Fixed Markup')->step('0.00000001'),
                Number::make('custom_price')->label('Custom Price')->step('0.00000001'),
                Number::make('min_selling_price')->label('Min Selling Price')->step('0.00000001'),
                Checkbox::make('is_enabled')->label('Enabled'),
                Checkbox::make('refill_eligible')->label('Refill Eligible'),
                Checkbox::make('cancel_eligible')->label('Cancel Eligible'),
                Checkbox::make('drip_feed')->label('Drip Feed'),
                Checkbox::make('subscription')->label('Subscription'),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('channel')->label('Platform'),
                TextColumn::make('type'),
                TextColumn::make('provider_service_id')->label('Provider ID'),
                TextColumn::make('provider_cost')->label('Cost'),
                TextColumn::make('selling_price')->label('Sell Price')->formatStateUsing(fn ($record) => $record->selling_price),
                ToggleColumn::make('is_enabled')->label('Enabled'),
            ])
            ->filters([])
            ->recordActions([
                Action::make('edit'),
                Action::make('view'),
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
            'index' => ListSmmServices::route('/'),
            'create' => CreateSmmService::route('/create'),
            'edit' => EditSmmService::route('/{record}/edit'),
            'view' => ViewSmmService::route('/{record}'),
        ];
    }
}
