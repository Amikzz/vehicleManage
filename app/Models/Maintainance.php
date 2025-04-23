<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintainance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'description',
        'mileage',
        'cost',
        'done_by',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getDescriptionAttribute($value)
    {
        return ucfirst($value);
    }
}
