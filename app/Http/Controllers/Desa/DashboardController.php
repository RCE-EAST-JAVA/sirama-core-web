<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $desa = Auth::user()->desa;

        // Filter pengajuan berdasarkan desa admin yang login
        $query = Pengajuan::with('user')
            ->whereHas('user', fn ($q) => $q->where('desa', $desa));

        $stats = [
            'total'              => (clone $query)->count(),
            'berkas_diterima'    => (clone $query)->where('status', 'berkas_diterima')->count(),
            'diverifikasi_desa'  => (clone $query)->where('status', 'diverifikasi_desa')->count(),
            'ditolak_desa'       => (clone $query)->where('status', 'ditolak_desa')->count(),
            'diteruskan'         => (clone $query)->whereIn('status', ['diverifikasi_kecamatan', 'selesai'])->count(),
        ];

        $pengajuan_terbaru = (clone $query)
            ->whereIn('status', ['berkas_diterima', 'ditolak_desa'])
            ->latest()
            ->take(10)
            ->get();

        return view('desa.dashboard', compact('stats', 'pengajuan_terbaru', 'desa'));
    }
}
