<?php

namespace App\Http\Controllers;

use App\Services\ElectricityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ElectricityLogController extends Controller
{
    protected $electricityService;

    // Inject ElectricityService ke dalam constructor
    public function __construct(ElectricityService $electricityService)
    {
        $this->electricityService = $electricityService;
    }

    /**
     * Menampilkan riwayat penggunaan listrik pengguna.
     */
    public function index(): JsonResponse
    {
        $logs = $this->electricityService->getUserLogs(auth()->id());

        return response()->json([
            'status' => 'success',
            'data' => $logs
        ], 200);
    }

    /**
     * Menyimpan log penggunaan listrik baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'usage_kwh' => 'required|numeric|min:1',
            'period_month' => 'required|string|max:7',
            'record_date' => 'required|date',
        ]);

        $log = $this->electricityService->createLog(auth()->id(), $validated);

        return response()->json([
            'message' => 'Penggunaan listrik berhasil dicatat!',
            'data' => $log
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'usage_kwh' => 'sometimes|numeric|min:1',
            'period_month' => 'sometimes|string|max:7',
            'record_date' => 'sometimes|date',
        ]);

        $log = $this->electricityService->updateLog(auth()->id(), $id, $validated);

        return response()->json([
            'message' => 'Penggunaan listrik berhasil diperbarui!',
            'data' => $log
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->electricityService->deleteLog(auth()->id(), $id);

        if (! $deleted) {
            return response()->json(['message' => 'Log listrik tidak ditemukan.'], 404);
        }

        return response()->json(['message' => 'Log listrik berhasil dihapus.'], 200);
    }
}