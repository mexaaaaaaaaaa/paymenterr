<?php

namespace App\Admin\Resources\Smm\Pages;

use App\Admin\Resources\Smm\SmmOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListSmmOrders extends ListRecords
{
    protected static string $resource = SmmOrderResource::class;
}
