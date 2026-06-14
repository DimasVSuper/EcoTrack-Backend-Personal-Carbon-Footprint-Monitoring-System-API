<?php

namespace App\Services;

use App\Models\ElectricityLog;

class ElectricityService
{
    private $emissionFactor = 0.87;

    public function getUserLogs(int $userId)
    {
        return ElectricityLog::where('user_id', $userId)
            ->orderBy('logging_date', 'desc')
            ->get();
    }

    public function createLog(int $userId, array $data): ElectricityLog
    {
        $calculatedEmission = $data['kwh'] * $this->emissionFactor;

        return ElectricityLog::create([
            'user_id' => $userId,
            'kwh' => $data['kwh'],
            'period' => $data['period'],
            'calculated_emission' => $calculatedEmission,
            'logging_date' => $data['logging_date'],
        ]);
    }
}
