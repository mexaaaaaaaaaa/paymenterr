<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusIncident extends Model
{
    protected $fillable = [
        'title', 'body', 'state', 'affected_services', 'published', 'started_at', 'resolved_at',
    ];

    protected $casts = [
        'affected_services' => 'array',
        'published' => 'boolean',
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->state !== 'resolved' || $this->resolved_at === null;
    }
}
