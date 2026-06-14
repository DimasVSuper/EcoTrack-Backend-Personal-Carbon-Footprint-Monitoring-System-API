<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricityLog extends Model
{
    protected $fillable = [
        'user_id',
        'usage_kwh',
        'period_month',
        'record_date',
        'emission_kg',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
