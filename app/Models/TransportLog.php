<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportLog extends Model
{
    protected $fillable = [
        'user_id',
        'transport_type_id',
        'distance_km',
        'activity_date',
        'emission_kg',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transportType()
    {
        return $this->belongsTo(TransportType::class);
    }
}
