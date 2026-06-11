<?php

namespace App\Services;

use App\Models\ElectricityLog;

class ElectricityService
{
    // Faktor emisi listrik Indonesia (0.87 kg CO2 per kWh)
    private $emissionFactor = 0.87;

    /**
     * Ambil semua riwayat pencatatan listrik milik user.
     */
    public function getUserLogs(int $userId)
    {
        return ElectricityLog::where('user_id', $userId)
            ->orderBy('logging_date', 'desc')
            ->get();
    }

    /**
     * Catat penggunaan listrik baru dan hitung emisinya.
     */
    public function createLog(int $userId, array $data): ElectricityLog
    {
        // Rumus emisi listrik: kWh * Faktor Emisi
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