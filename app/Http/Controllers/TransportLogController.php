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
        // Validasi input dari aplikasi Flutter
        $validated = $request->validate([
            'transport_type_id' => 'required|exists:transport_types,id',
            'distance' => 'required|numeric|min:0.1',
            'activity_date' => 'required|date',
        ]);

        // Lempar data ke service beserta ID user yang sedang login
        $log = $this->transportService->createLog(auth()->id(), $validated);

        return response()->json([
            'message' => 'Aktivitas transportasi berhasil dicatat!',
            'data' => $log->load('transportType') // Load relasi agar Flutter mendapat info nama kendaraannya
        ], 201);
    }
}