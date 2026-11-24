<?php

namespace App\Admin\Resources\StatusIncidentResource\Pages;

use App\Admin\Resources\StatusIncidentResource;
use App\Models\StatusIncident;
use Filament\Resources\Pages\CreateRecord;

class CreateStatusIncident extends CreateRecord
{
    protected static string $resource = StatusIncidentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (($data['state'] ?? '') === 'resolved' && empty($data['resolved_at'])) {
            $data['resolved_at'] = now();
        }

        return $data;
    }
}
