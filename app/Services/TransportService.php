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

    public function updateLog(int $userId, int $logId, array $data): TransportLog
    {
        $log = TransportLog::where('id', $logId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $emissionKg = $this->emissionCalculator->calculateTransportEmission(
            $data['distance_km'] ?? $log->distance_km,
            $data['transport_type_id'] ?? $log->transport_type_id
        );

        $log->update([
            'transport_type_id' => $data['transport_type_id'] ?? $log->transport_type_id,
            'distance_km' => $data['distance_km'] ?? $log->distance_km,
            'activity_date' => $data['activity_date'] ?? $log->activity_date,
            'emission_kg' => $emissionKg,
        ]);

        return $log->fresh();
    }

    public function deleteLog(int $userId, int $logId): bool
    {
        return TransportLog::where('id', $logId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }
}
