<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pengajuan::with('user')
            ->whereIn('status', ['selesai', 'ditolak_kecamatan', 'ditolak_desa']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        if ($request->filled('desa')) {
            $query->where('desa', $request->desa);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('updated_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('updated_at', '<=', $request->tanggal_sampai);
        }

        $pengajuans = $query->latest('updated_at')->paginate(15)->withQueryString();

        $desas = \App\Models\Desa::orderBy('nama')->pluck('nama');

        return view('admin.riwayat.index', compact('pengajuans', 'desas'));
    }
}
