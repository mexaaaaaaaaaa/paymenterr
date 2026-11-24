<?php

namespace App\Admin\Resources;

use App\Admin\Resources\StatusIncidentResource\Pages\CreateStatusIncident;
use App\Admin\Resources\StatusIncidentResource\Pages\EditStatusIncident;
use App\Admin\Resources\StatusIncidentResource\Pages\ListStatusIncidents;
use App\Models\StatusIncident;
use App\Services\StatusMonitor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StatusIncidentResource extends Resource
{
    protected static ?string $model = StatusIncident::class;

    protected static string|\BackedEnum|null $navigationIcon = 'ri-alert-line';

    public static function getNavigationLabel(): string
    {
        return __('Status Incidents');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Title')->required()->maxLength(255),
            Select::make('state')->label('State')->options([
                'investigating' => 'Investigating',
                'identified' => 'Identified',
                'monitoring' => 'Monitoring',
                'maintenance' => 'Maintenance',
                'resolved' => 'Resolved',
            ])->default('investigating')->required(),
            Select::make('affected_services')->label('Affected services')
                ->options(collect(StatusMonitor::SERVICES)->map(fn ($s) => $s['name'])->all())
                ->multiple()->searchable(),
            Textarea::make('body')->label('Description')->rows(4),
            Checkbox::make('published')->label('Published (visible on /status)')->default(true),
            DateTimePicker::make('started_at')->label('Started at')->default(now()),
            DateTimePicker::make('resolved_at')->label('Resolved at')->helperText('Set automatically when state is resolved'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('state')->badge()->colors([
                    'danger' => 'investigating',
                    'warning' => 'identified',
                    'primary' => 'monitoring',
                    'info' => 'maintenance',
                    'success' => 'resolved',
                ]),
                IconColumn::make('published')->boolean()->label('Published'),
                TextColumn::make('started_at')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('resolved_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([EditAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStatusIncidents::route('/'),
            'create' => CreateStatusIncident::route('/create'),
            'edit' => EditStatusIncident::route('/{record}/edit'),
        ];
    }
}
