<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterRead extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'mileage',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
