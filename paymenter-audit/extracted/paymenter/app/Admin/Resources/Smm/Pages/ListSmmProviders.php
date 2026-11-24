<?php

namespace App\Admin\Resources\Smm\Pages;

use App\Admin\Resources\Smm\SmmProviderResource;
use Filament\Resources\Pages\ListRecords;

class ListSmmProviders extends ListRecords
{
    protected static string $resource = SmmProviderResource::class;
}
