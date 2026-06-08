<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'tenant_id', 'service_id', 'client_name', 'client_whatsapp',
        'scheduled_at', 'ends_at', 'status'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ends_at'      => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}