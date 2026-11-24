<?php

namespace App\Admin\Resources\Smm\Pages;

use App\Admin\Resources\Smm\SmmServiceResource;
use Filament\Resources\Pages\EditRecord;

class EditSmmService extends EditRecord
{
    protected static string $resource = SmmServiceResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
