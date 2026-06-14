<?php

namespace App\Services;

use App\Models\ElectricityLog;

class ElectricityService
{
    private $emissionCalculator;

    public function __construct(EmissionCalculatorService $emissionCalculator)
    {
        $this->emissionCalculator = $emissionCalculator;
    }

    public function getUserLogs(int $userId)
    {
        return ElectricityLog::where('user_id', $userId)
            ->orderBy('record_date', 'desc')
            ->get();
    }

    public function createLog(int $userId, array $data): ElectricityLog
    {
        $emissionKg = $this->emissionCalculator->calculateElectricityEmission($data['usage_kwh']);

        return ElectricityLog::create([
            'user_id' => $userId,
            'usage_kwh' => $data['usage_kwh'],
            'period_month' => $data['period_month'],
            'emission_kg' => $emissionKg,
            'record_date' => $data['record_date'],
        ]);
    }
}
