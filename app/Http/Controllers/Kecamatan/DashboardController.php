<?php

namespace App\Http\Controllers\Kecamatan;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total'                => Pengajuan::count(),
            'menunggu_verifikasi'  => Pengajuan::where('status', 'diverifikasi_desa')->count(),
            'diproses'             => Pengajuan::where('status', 'diproses_kecamatan')->count(),
            'ditolak'              => Pengajuan::where('status', 'ditolak_kecamatan')->count(),
            'selesai'              => Pengajuan::where('status', 'selesai')->count(),
        ];

        // Statistik per jenis layanan
        $per_jenis = Pengajuan::selectRaw('jenis_layanan, count(*) as total')
            ->groupBy('jenis_layanan')
            ->pluck('total', 'jenis_layanan');

        $pengajuan_terbaru = Pengajuan::with('user')
            ->whereIn('status', ['diverifikasi_desa', 'diproses_kecamatan'])
            ->latest()
            ->take(10)
            ->get();

        return view('kecamatan.dashboard', compact('stats', 'per_jenis', 'pengajuan_terbaru'));
    }
}
