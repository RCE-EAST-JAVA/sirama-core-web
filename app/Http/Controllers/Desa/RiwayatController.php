<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    public function index(Request $request): View
    {
        $desa = Auth::user()->desa;

        $query = Pengajuan::with('user')
            ->whereHas('user', fn ($q) => $q->where('desa', $desa))
            ->whereIn('status', ['selesai', 'ditolak_kecamatan', 'ditolak_desa', 'diverifikasi_desa', 'diverifikasi_kecamatan', 'diajukan_kembali']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('updated_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('updated_at', '<=', $request->tanggal_sampai);
        }

        $pengajuans = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('desa.riwayat.index', compact('pengajuans'));
    }
}
