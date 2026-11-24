<?php

namespace App\Admin\Resources\Smm\Pages;

use App\Admin\Resources\Smm\SmmCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListSmmCategories extends ListRecords
{
    protected static string $resource = SmmCategoryResource::class;
}
