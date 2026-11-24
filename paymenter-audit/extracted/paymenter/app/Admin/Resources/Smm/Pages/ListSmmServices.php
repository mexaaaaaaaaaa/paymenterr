<?php

namespace App\Admin\Resources\Smm\Pages;

use App\Admin\Resources\Smm\SmmServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSmmServices extends ListRecords
{
    protected static string $resource = SmmServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
