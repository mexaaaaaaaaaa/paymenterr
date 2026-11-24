<?php

namespace App\Admin\Resources\StatusIncidentResource\Pages;

use App\Admin\Resources\StatusIncidentResource;
use Filament\Resources\Pages\EditRecord;

class EditStatusIncident extends EditRecord
{
    protected static string $resource = StatusIncidentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['state'] ?? '') === 'resolved' && empty($data['resolved_at'])) {
            $data['resolved_at'] = now();
        }

        return $data;
    }
}
