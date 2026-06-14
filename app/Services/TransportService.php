<?php

namespace App\Services;

use App\Models\TransportLog;
use App\Models\TransportType;

class TransportService
{
    private $emissionCalculator;

    public function __construct(EmissionCalculatorService $emissionCalculator)
    {
        $this->emissionCalculator = $emissionCalculator;
    }

    public function getUserLogs(int $userId)
    {
        return TransportLog::with('transportType')
            ->where('user_id', $userId)
            ->orderBy('activity_date', 'desc')
            ->get();
    }

    public function createLog(int $userId, array $data): TransportLog
    {
        $emissionKg = $this->emissionCalculator->calculateTransportEmission($data['distance_km'], $data['transport_type_id']);

        return TransportLog::create([
            'user_id' => $userId,
            'transport_type_id' => $data['transport_type_id'],
            'distance_km' => $data['distance_km'],
            'emission_kg' => $emissionKg,
            'activity_date' => $data['activity_date'],
        ]);
    }
}
