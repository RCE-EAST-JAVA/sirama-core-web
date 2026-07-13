<?php

namespace App\Http\Controllers\Kecamatan;

use App\Events\StatusPengajuanUpdated;
use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $query->whereIn('status', ['diverifikasi_desa', 'diproses_kecamatan']);
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
            'aksi'    => ['required', 'in:approve,tolak,selesai'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $statusBaru = match ($request->aksi) {
            'approve' => 'diproses_kecamatan',
            'tolak'   => 'ditolak_kecamatan',
            'selesai' => 'selesai',
        };

        $pengajuan->update(['status' => $statusBaru]);

        RiwayatStatus::create([
            'pengajuan_id'   => $pengajuan->id,
            'status_riwayat' => $statusBaru,
            'catatan'        => $request->catatan,
        ]);

        event(new StatusPengajuanUpdated($pengajuan));

        $pesan = match ($request->aksi) {
            'approve' => 'Pengajuan sedang diproses.',
            'tolak'   => 'Pengajuan dikembalikan ke desa untuk ditinjau ulang.',
            'selesai' => 'Pengajuan selesai.',
        };

        return redirect()->route('kecamatan.pengajuan.show', $pengajuan)
            ->with('success', $pesan);
    }

    public function uploadSoftfile(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        $request->validate([
            'softfile'   => ['required', 'array', 'min:1'],
            'softfile.*' => ['file', 'mimes:pdf', 'max:10240'],
        ]);

        $existing = $pengajuan->lokasi_dokumen ?? [];

        foreach ($request->file('softfile') as $file) {
            $originalName = $file->getClientOriginalName();
            // Simpan dengan nama asli; tambahkan timestamp jika nama sudah ada
            $targetName = $originalName;
            $targetDir  = 'softfile/' . $pengajuan->id;

            if (Storage::disk('local')->exists($targetDir . '/' . $targetName)) {
                $ext        = pathinfo($originalName, PATHINFO_EXTENSION);
                $base       = pathinfo($originalName, PATHINFO_FILENAME);
                $targetName = $base . '_' . time() . '.' . $ext;
            }

            $path     = $file->storeAs($targetDir, $targetName, 'local');
            $existing[] = $path;
        }

        $pengajuan->update(['lokasi_dokumen' => $existing]);

        return redirect()->route('kecamatan.pengajuan.show', $pengajuan)
            ->with('success', count($request->file('softfile')) . ' softfile berhasil diupload.');
    }

    public function hapusSoftfile(Request $request, Pengajuan $pengajuan): RedirectResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $existing = $pengajuan->lokasi_dokumen ?? [];

        // Security: pastikan path yang diminta memang milik pengajuan ini
        if (! in_array($request->path, $existing)) {
            return redirect()->route('kecamatan.pengajuan.show', $pengajuan)
                ->with('error', 'File tidak ditemukan.');
        }

        // Hapus file dari storage
        if (Storage::disk('local')->exists($request->path)) {
            Storage::disk('local')->delete($request->path);
        }

        // Hapus dari array dan simpan
        $updated = array_values(array_filter($existing, fn($p) => $p !== $request->path));
        $pengajuan->update(['lokasi_dokumen' => $updated ?: null]);

        return redirect()->route('kecamatan.pengajuan.show', $pengajuan)
            ->with('success', 'Softfile berhasil dihapus.');
    }
}
