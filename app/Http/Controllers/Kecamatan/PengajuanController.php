<?php

namespace App\Http\Controllers\Kecamatan;

use App\Events\StatusPengajuanUpdated;
use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pengajuan::with('user');

        // Default: tampilkan yang perlu ditindaklanjuti kecamatan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['diverifikasi_desa', 'diproses_kecamatan', 'ditolak_kecamatan']);
        }

        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        $pengajuans = $query->latest()->paginate(15)->withQueryString();

        return view('kecamatan.pengajuan.index', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan): View
    {
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

        return view('kecamatan.pengajuan.show', compact('pengajuan', 'formDetail'));
    }

    public function proses(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        $request->validate([
            'aksi'          => ['required', 'in:approve,tolak,selesai'],
            'catatan'       => ['nullable', 'string', 'max:1000'],
            'softfile'      => ['nullable', 'file', 'mimes:pdf', 'max:10240', 'required_if:aksi,selesai'],
        ]);

        $statusBaru = match ($request->aksi) {
            'approve' => 'diproses_kecamatan',
            'tolak'   => 'ditolak_kecamatan',
            'selesai' => 'selesai',
        };

        $data = ['status' => $statusBaru];

        // Upload softfile jika aksi selesai
        if ($request->aksi === 'selesai' && $request->hasFile('softfile')) {
            $path = $request->file('softfile')->store(
                'softfile/'.$pengajuan->id,
                'local'
            );
            $data['lokasi_dokumen'] = $path;
        }

        // Jika ditolak kecamatan, kembalikan ke diverifikasi_desa agar desa bisa review ulang
        if ($request->aksi === 'tolak') {
            $data['status'] = 'ditolak_kecamatan';
        }

        $pengajuan->update($data);

        RiwayatStatus::create([
            'pengajuan_id'   => $pengajuan->id,
            'status_riwayat' => $statusBaru,
            'catatan'        => $request->catatan,
        ]);

        event(new StatusPengajuanUpdated($pengajuan));

        $pesan = match ($request->aksi) {
            'approve' => 'Pengajuan sedang diproses.',
            'tolak'   => 'Pengajuan dikembalikan ke desa untuk ditinjau ulang.',
            'selesai' => 'Pengajuan selesai. Softfile berhasil dikirim ke pemohon.',
        };

        return redirect()->route('kecamatan.pengajuan.index')
            ->with('success', $pesan);
    }
}
