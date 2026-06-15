<?php

namespace App\Http\Controllers;

use App\Services\TransportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransportLogController extends Controller
{
    protected $transportService;

    // Inject langsung TransportService ke dalam constructor
    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    /**
     * Menampilkan riwayat transportasi pengguna.
     */
    public function index(): JsonResponse
    {
        $logs = $this->transportService->getUserLogs(auth()->id());

        return response()->json([
            'status' => 'success',
            'data' => $logs
        ], 200);
    }

    /**
     * Menyimpan log transportasi baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'transport_type_id' => 'required|exists:transport_types,id',
            'distance_km' => 'required|numeric|min:0.1',
            'activity_date' => 'required|date',
        ]);

        $log = $this->transportService->createLog(auth()->id(), $validated);

        return response()->json([
            'message' => 'Aktivitas transportasi berhasil dicatat!',
            'data' => $log->load('transportType')
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'transport_type_id' => 'sometimes|exists:transport_types,id',
            'distance_km' => 'sometimes|numeric|min:0.1',
            'activity_date' => 'sometimes|date',
        ]);

        $log = $this->transportService->updateLog(auth()->id(), $id, $validated);

        return response()->json([
            'message' => 'Aktivitas transportasi berhasil diperbarui!',
            'data' => $log->load('transportType')
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->transportService->deleteLog(auth()->id(), $id);

        if (! $deleted) {
            return response()->json(['message' => 'Log transportasi tidak ditemukan.'], 404);
        }

        return response()->json(['message' => 'Log transportasi berhasil dihapus.'], 200);
    }
}