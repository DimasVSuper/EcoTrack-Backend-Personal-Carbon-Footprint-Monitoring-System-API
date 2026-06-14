<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportType extends Model
{
    protected $fillable = [
        'name',
        'emission_factor_per_km',
    ];

    public function transportLogs()
    {
        return $this->hasMany(TransportLog::class);
    }
}
