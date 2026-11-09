<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoadTestController extends Controller
{
    /**
     * Tampilkan halaman antarmuka Database Load Test.
     */
    public function index(): View
    {
        try {
            $totalPengajuan = Pengajuan::count();
            $totalUsers = User::count();
            $totalData = $totalPengajuan > 0 ? $totalPengajuan : $totalUsers;
        } catch (\Throwable $e) {
            $totalData = 48; // Fallback jika DB belum dinyalakan di local environment
            $totalPengajuan = 0;
            $totalUsers = 0;
        }

        $dbConnection = config('database.default', 'mysql');
        $dbDatabase = config("database.connections.{$dbConnection}.database", 'db_sirama');

        return view('admin.load-test.index', [
            'total_data' => $totalData,
            'total_pengajuan' => $totalPengajuan,
            'total_users' => $totalUsers,
            'batch_size' => 10,
            'db_connection' => $dbConnection,
            'db_database' => $dbDatabase,
        ]);
    }

    /**
     * Eksekusi satu request uji beban database (mengukur waktu eksekusi query).
     */
    public function run(Request $request): JsonResponse
    {
        $startTime = hrtime(true);
        $queriesCount = 0;

        try {
            DB::listen(function () use (&$queriesCount) {
                $queriesCount++;
            });

            // 1. Eksekusi query dengan relasi eager loading & ordering
            $records = Pengajuan::with('user')
                ->latest('id')
                ->limit(10)
                ->get();

            // 2. Fallback jika tabel pengajuan masih kosong, query user
            if ($records->isEmpty()) {
                $records = User::latest('id')->limit(10)->get();
            }

            $endTime = hrtime(true);
            $durationMs = ($endTime - $startTime) / 1_000_000; // konversi nanodetik ke milidetik

            return response()->json([
                'success' => true,
                'batch_index' => (int) $request->input('batch_index', 1),
                'server_execution_time_ms' => round($durationMs, 2),
                'queries_count' => $queriesCount,
                'records_retrieved' => $records->count(),
                'memory_peak_mb' => round(memory_get_peak_usage(true) / (1024 * 1024), 2),
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Throwable $e) {
            $endTime = hrtime(true);
            $durationMs = ($endTime - $startTime) / 1_000_000;

            return response()->json([
                'success' => false,
                'batch_index' => (int) $request->input('batch_index', 1),
                'server_execution_time_ms' => round($durationMs, 2),
                'message' => 'Gagal mengeksekusi query database: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'timestamp' => now()->toIso8601String(),
            ], 500);
        }
    }
}
