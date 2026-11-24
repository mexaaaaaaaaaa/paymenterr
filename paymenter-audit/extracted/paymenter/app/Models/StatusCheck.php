<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusCheck extends Model
{
    public $timestamps = false;

    protected $fillable = ['service', 'status', 'latency_ms', 'details', 'checked_at'];

    protected $casts = [
        'checked_at' => 'datetime',
        'latency_ms' => 'integer',
    ];
}
