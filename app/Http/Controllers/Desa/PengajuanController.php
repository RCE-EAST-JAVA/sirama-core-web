<?php

namespace App\Http\Controllers\Desa;

use App\Events\StatusPengajuanUpdated;
use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function index(Request $request): View
    {
        $desa = Auth::user()->desa;

        $query = Pengajuan::with('user')
            ->whereHas('user', fn ($q) => $q->where('desa', $desa));

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan yang perlu ditindaklanjuti
            $query->whereIn('status', ['berkas_diterima', 'ditolak_desa', 'diajukan_kembali']);
        }

        // Filter jenis layanan
        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        $pengajuans = $query->latest()->paginate(15)->withQueryString();

        return view('desa.pengajuan.index', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan): View
    {
        $this->authorizeDesa($pengajuan);

        $pengajuan->load([
            'user',
            'riwayatStatuses',
            'ocrResults',
            'formKia',
            'form3In1',
            'formKkPenambahan',
            'formKkPengurangan',
            'formKkPerbaikan.jenisPerbaikan',
        ]);

        $formDetail = $pengajuan->getFormDetail();

        return view('desa.pengajuan.show', compact('pengajuan', 'formDetail'));
    }

    public function verifikasi(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        $this->authorizeDesa($pengajuan);

        $request->validate([
            'aksi'    => ['required', 'in:approve,tolak'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $statusBaru = $request->aksi === 'approve'
            ? 'diverifikasi_desa'
            : 'ditolak_desa';

        $pengajuan->update(['status' => $statusBaru]);

        RiwayatStatus::create([
            'pengajuan_id'   => $pengajuan->id,
            'status_riwayat' => $statusBaru,
            'catatan'        => $request->catatan,
        ]);

        event(new StatusPengajuanUpdated($pengajuan));

        $pesan = $request->aksi === 'approve'
            ? 'Pengajuan berhasil diverifikasi dan diteruskan ke kecamatan.'
            : 'Pengajuan ditolak. Pemohon akan diminta untuk merevisi berkas.';

        return redirect()->route('desa.pengajuan.index')
            ->with('success', $pesan);
    }

    /**
     * Pastikan pengajuan berasal dari desa yang sama dengan admin login.
     */
    private function authorizeDesa(Pengajuan $pengajuan): void
    {
        $desa = Auth::user()->desa;
        abort_unless(
            $pengajuan->user->desa === $desa,
            403,
            'Pengajuan ini bukan dari desa Anda.'
        );
    }
}
