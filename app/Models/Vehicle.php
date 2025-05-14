<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'make',
        'model',
        'year',
        'color',
        'license_plate',
        'license_expiration_date',
        'insurance_expiration_date',
        'type',
        'status',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function maintainances()
    {
        return $this->hasMany(Maintainance::class);
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function meterReads()
    {
        return $this->hasMany(MeterRead::class);
    }
}
