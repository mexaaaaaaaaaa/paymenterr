<?php

namespace App\Admin\Resources\StatusIncidentResource\Pages;

use App\Admin\Resources\StatusIncidentResource;
use Filament\Resources\Pages\ListRecords;

class ListStatusIncidents extends ListRecords
{
    protected static string $resource = StatusIncidentResource::class;
}
