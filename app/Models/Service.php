<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_type',
        'service_date',
        'service_location',
        'service_cost',
        'mileage',
        'next_service_mileage',
        'next_service_date',
        'service_notes',
        'done_by',
    ];

    // App\Models\Service.php
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function getServiceTypeAttribute($value)
    {
        return ucfirst($value);
    }
}
