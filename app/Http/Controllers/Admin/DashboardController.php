<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_pengajuan'      => Pengajuan::count(),
            'berkas_diterima'      => Pengajuan::where('status', 'berkas_diterima')->count(),
            'diverifikasi_desa'    => Pengajuan::where('status', 'diverifikasi_desa')->count(),
            'diverifikasi_kecamatan' => Pengajuan::where('status', 'diverifikasi_kecamatan')->count(),
            'selesai'              => Pengajuan::where('status', 'selesai')->count(),
            'ditolak_desa'         => Pengajuan::where('status', 'ditolak_desa')->count(),
            'ditolak_kecamatan'    => Pengajuan::where('status', 'ditolak_kecamatan')->count(),
            'total_admin_desa'     => User::where('role', 'admin_desa')->count(),
            'total_admin_kecamatan'=> User::where('role', 'admin_kecamatan')->count(),
            'total_warga'          => User::where('role', 'warga')->count(),
        ];

        $pengajuan_terbaru = Pengajuan::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pengajuan_terbaru'));
    }
}
