<?php

namespace App\Admin\Resources\Smm;

use App\Admin\Resources\Smm\Pages\ListSmmCategories;
use App\Admin\Resources\Smm\Pages\ViewSmmCategory;
use Paymenter\Extensions\Others\Smm\Models\SmmCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Number;
use Filament\Forms\Components\Checkbox;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SmmCategoryResource extends Resource
{
    protected static ?string $model = SmmCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'ri-folder-6-line';

    protected static string|\UnitEnum|null $navigationGroup = 'SMM Hub';

    protected static ?int $navigationSort = 4;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('slug')->required(),
                TextInput::make('platform')->maxLength(50),
                Textarea::make('description'),
                Number::make('sort'),
                Number::make('markup_percent')->step('0.0001')->label('Markup %'),
                Checkbox::make('is_active'),
                Select::make('parent_id')->relationship('parent', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
                TextColumn::make('platform'),
                ToggleColumn::make('is_active'),
                TextColumn::make('services_count')->counts('services'),
            ])
            ->filters([])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSmmCategories::route('/'),
            'view' => ViewSmmCategory::route('/{record}'),
        ];
    }
}
