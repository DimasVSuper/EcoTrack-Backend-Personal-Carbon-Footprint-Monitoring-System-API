<?php

namespace App\Services;

use App\Models\TransportLog;
use App\Models\TransportType;

class TransportService
{
    /**
     * Ambil semua riwayat perjalanan milik user tertentu.
     */
    public function getUserLogs(int $userId)
    {
        return TransportLog::with('transportType')
            ->where('user_id', $userId)
            ->orderBy('activity_date', 'desc')
            ->get();
    }

    /**
     * Catat perjalanan baru dan hitung emisinya secara otomatis.
     */
    public function createLog(int $userId, array $data): TransportLog
    {
        // Cari data kendaraan untuk mendapatkan faktor emisinya
        $transportType = TransportType::findOrFail($data['transport_type_id']);

        // Rumus inti kalkulasi emisi karbon transportasi
        $calculatedEmission = $data['distance'] * $transportType->emission_factor;

        // Simpan ke database
        return TransportLog::create([
            'user_id' => $userId,
            'transport_type_id' => $data['transport_type_id'],
            'distance' => $data['distance'],
            'calculated_emission' => $calculatedEmission,
            'activity_date' => $data['activity_date'],
        ]);
    }
}