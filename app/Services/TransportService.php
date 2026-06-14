<?php

namespace App\Services;

use App\Models\TransportLog;
use App\Models\TransportType;

class TransportService
{
    public function getUserLogs(int $userId)
    {
        return TransportLog::with('transportType')
            ->where('user_id', $userId)
            ->orderBy('activity_date', 'desc')
            ->get();
    }

    public function createLog(int $userId, array $data): TransportLog
    {
        $transportType = TransportType::findOrFail($data['transport_type_id']);
        $calculatedEmission = $data['distance'] * $transportType->emission_factor;

        return TransportLog::create([
            'user_id' => $userId,
            'transport_type_id' => $data['transport_type_id'],
            'distance' => $data['distance'],
            'calculated_emission' => $calculatedEmission,
            'activity_date' => $data['activity_date'],
        ]);
    }
}
