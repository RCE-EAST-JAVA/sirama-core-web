<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Desa\StorePengajuanDesaAktaKematianRequest;
use App\Http\Requests\Desa\StorePengajuanDesaAktaLahirRequest;
use App\Http\Requests\Desa\StorePengajuanDesaKiaRequest;
use App\Http\Requests\Desa\StorePengajuanDesaKkBaruRequest;
use App\Http\Requests\Desa\StorePengajuanDesaKkPenambahanRequest;
use App\Http\Requests\Desa\StorePengajuanDesaKkPenguranganRequest;
use App\Http\Requests\Desa\StorePengajuanDesaKkPerbaikanRequest;
use App\Http\Requests\Desa\StorePengajuanDesaTiga1Request;
use App\Models\FormAktaKematian;
use App\Models\FormAktaLahir;
use App\Models\Form3In1;
use App\Models\FormKia;
use App\Models\FormKkBaru;
use App\Models\FormKkPenambahan;
use App\Models\FormKkPengurangan;
use App\Models\FormKkPerbaikan;
use App\Models\MasterJenisPerbaikanKk;
use App\Models\Pengajuan;
use App\Services\DesaPengajuanStoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BuatPengajuanController extends Controller
{
    private const JENIS_LAYANAN_VALID = [
        'kia', '3_in_1', 'kk_penambahan', 'kk_pengurangan',
        'kk_perbaikan', 'akta_kelahiran', 'akta_kematian', 'kk_baru',
    ];

    public function __construct(private DesaPengajuanStoreService $service)
    {
    }

    /**
     * Halaman pilih jenis layanan.
     */
    public function index(): View
    {
        return view('desa.pengajuan.buat.index');
    }

    /**
     * Tampilkan form pengajuan sesuai jenis layanan.
     */
    public function create(string $jenis): View
    {
        abort_unless(in_array($jenis, self::JENIS_LAYANAN_VALID), 404);

        $desa              = Auth::user()->desa;
        $jenisPerbaikanList = $jenis === 'kk_perbaikan'
            ? MasterJenisPerbaikanKk::orderBy('nama_perbaikan')->get()
            : collect();

        return view('desa.pengajuan.buat.create', compact('jenis', 'desa', 'jenisPerbaikanList'));
    }

    /**
     * Proses submit form pengajuan.
     */
    public function store(Request $request, string $jenis): RedirectResponse
    {
        abort_unless(in_array($jenis, self::JENIS_LAYANAN_VALID), 404);

        // Resolve & validate FormRequest yang sesuai
        $validated = $this->resolveAndValidate($request, $jenis);

        $desa    = Auth::user()->desa;
        $pemohon = $request->only([
            'nama_lengkap', 'nik', 'no_whatsapp', 'tanggal_lahir',
            'jenis_kelamin', 'pekerjaan', 'alamat', 'rt', 'rw',
        ]);

        DB::transaction(function () use ($request, $jenis, $pemohon, $desa) {
            $warga     = $this->service->createOrFindWarga($pemohon, $desa);
            $pengajuan = Pengajuan::create(
                $this->service->buildPengajuanData($warga, $jenis, $pemohon, $desa)
            );

            $this->createFormDetail($request, $jenis, $pengajuan);
            $this->service->catatRiwayat($pengajuan);
            $this->service->notifyKecamatan($pengajuan);
        });

        return redirect()->route('desa.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dibuat dan langsung diteruskan ke kecamatan.');
    }

    /**
     * Resolve FormRequest yang sesuai dan jalankan validasi.
     * Mengembalikan array data yang sudah divalidasi.
     */
    private function resolveAndValidate(Request $request, string $jenis): array
    {
        $formRequestClass = match ($jenis) {
            'kia'            => StorePengajuanDesaKiaRequest::class,
            '3_in_1'         => StorePengajuanDesaTiga1Request::class,
            'kk_penambahan'  => StorePengajuanDesaKkPenambahanRequest::class,
            'kk_pengurangan' => StorePengajuanDesaKkPenguranganRequest::class,
            'kk_perbaikan'   => StorePengajuanDesaKkPerbaikanRequest::class,
            'akta_kelahiran' => StorePengajuanDesaAktaLahirRequest::class,
            'akta_kematian'  => StorePengajuanDesaAktaKematianRequest::class,
            'kk_baru'        => StorePengajuanDesaKkBaruRequest::class,
        };

        return app($formRequestClass)->validated();
    }

    /**
     * Buat record form detail dan simpan semua file sesuai jenis layanan.
     */
    private function createFormDetail(Request $request, string $jenis, Pengajuan $pengajuan): void
    {
        $folder = 'pengajuan/' . str_replace('_', '-', $jenis);

        match ($jenis) {
            'kia' => FormKia::create([
                'pengajuan_id'         => $pengajuan->id,
                'nama_lengkap'         => $request->nama_lengkap_anak,
                'tempat_lahir'         => $request->tempat_lahir_anak,
                'tanggal_lahir'        => $request->tanggal_lahir_anak,
                'jenis_kelamin'        => $request->jenis_kelamin_anak,
                'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
                'agama'                => $request->agama,
                'kewarganegaraan'      => $request->kewarganegaraan,
                'file_akta_kelahiran'  => $this->service->storeFile($request->file('file_akta_kelahiran'), $folder),
                'file_kk'              => $this->service->storeFile($request->file('file_kk'), $folder),
                'file_surat_nikah'     => $this->service->storeFile($request->file('file_surat_nikah'), $folder),
                'file_foto_anak'       => $this->service->storeFile($request->file('file_foto_anak'), $folder),
            ]),

            '3_in_1' => Form3In1::create([
                'pengajuan_id'       => $pengajuan->id,
                'nama_anak'          => $request->nama_anak,
                'tanggal_lahir_anak' => $request->tanggal_lahir_anak,
                'file_sk_lahir'      => $this->service->storeFile($request->file('file_sk_lahir'), $folder),
                'file_kk'            => $this->service->storeFile($request->file('file_kk'), $folder),
                'file_ktp_ortu'      => $this->service->storeFile($request->file('file_ktp_ortu'), $folder),
                'file_surat_nikah'   => $this->service->storeFile($request->file('file_surat_nikah'), $folder),
                'file_foto_anak'     => $this->service->storeFile($request->file('file_foto_anak'), $folder),
            ]),

            'kk_penambahan' => FormKkPenambahan::create([
                'pengajuan_id'           => $pengajuan->id,
                'nama_kepala_keluarga'   => $request->nama_kepala_keluarga,
                'nomor_kk'               => $request->nomor_kk,
                'nama_ketua_rt'          => $request->nama_ketua_rt,
                'nama_ketua_rw'          => $request->nama_ketua_rw,
                'nama_lengkap_tambahan'  => $request->nama_lengkap_tambahan,
                'jenis_kelamin_tambahan' => $request->jenis_kelamin_tambahan,
                'tempat_lahir_tambahan'  => $request->tempat_lahir_tambahan,
                'tanggal_lahir_tambahan' => $request->tanggal_lahir_tambahan,
                'status_hubungan'        => $request->status_hubungan,
                'kelainan_fisik_mental'  => $request->kelainan_fisik_mental,
                'penyandang_cacat'       => $request->penyandang_cacat,
                'agama'                  => $request->agama,
                'nama_ibu_kandung'       => $request->nama_ibu_kandung,
                'nik_ibu'                => $request->nik_ibu,
                'nama_ayah_kandung'      => $request->nama_ayah_kandung,
                'nik_ayah'               => $request->nik_ayah,
                'file_kk_asli'           => $this->service->storeFile($request->file('file_kk_asli'), $folder),
                'file_sk_lahir_akta'     => $this->service->storeFile($request->file('file_sk_lahir_akta'), $folder),
                'file_ktp_suami_istri'   => $this->service->storeFile($request->file('file_ktp_suami_istri'), $folder),
                'file_surat_nikah'       => $this->service->storeFile($request->file('file_surat_nikah'), $folder),
            ]),

            'kk_pengurangan' => FormKkPengurangan::create([
                'pengajuan_id'           => $pengajuan->id,
                'alasan_pengurangan'     => $request->alasan_pengurangan,
                'nama_lengkap_anggota'   => $request->nama_lengkap_anggota,
                'alamat_lengkap_anggota' => $request->alamat_lengkap_anggota,
                'nik_anggota'            => $request->nik_anggota,
                'file_kk_asli'           => $this->service->storeFile($request->file('file_kk_asli'), $folder),
                'file_ktp_asli'          => $this->service->storeFile($request->file('file_ktp_asli'), $folder),
                'file_sk_pindah_mati'    => $this->service->storeFile($request->file('file_sk_pindah_mati'), $folder),
            ]),

            'kk_perbaikan' => (function () use ($request, $pengajuan, $folder) {
                $files = $request->file('file_pendukung') ?? [];
                if (!is_array($files)) $files = [$files];
                $filePaths = collect($files)
                    ->filter()
                    ->map(fn($f) => $this->service->storeFile($f, $folder))
                    ->values()
                    ->all();

                FormKkPerbaikan::create([
                    'pengajuan_id'                 => $pengajuan->id,
                    'jenis_perbaikan_id'           => $request->jenis_perbaikan_id,
                    'nama_kepala_keluarga'         => $request->nama_kepala_keluarga,
                    'nomor_kk'                     => $request->nomor_kk,
                    'nama_anggota_yang_diperbaiki' => $request->nama_anggota_yang_diperbaiki,
                    'data_perbaikan'               => $request->data_perbaikan,
                    'file_pendukung'               => $filePaths,
                ]);
            })(),

            'akta_kelahiran' => FormAktaLahir::create([
                'pengajuan_id'       => $pengajuan->id,
                'nama_anak'          => $request->nama_anak,
                'tanggal_lahir_anak' => $request->tanggal_lahir_anak,
                'file_sk_lahir'      => $this->service->storeFile($request->file('file_sk_lahir'), $folder),
                'file_kk'            => $this->service->storeFile($request->file('file_kk'), $folder),
                'file_ktp_ayah'      => $this->service->storeFile($request->file('file_ktp_ayah'), $folder),
                'file_ktp_ibu'       => $this->service->storeFile($request->file('file_ktp_ibu'), $folder),
                'file_surat_nikah'   => $this->service->storeFile($request->file('file_surat_nikah'), $folder),
            ]),

            'akta_kematian' => FormAktaKematian::create([
                'pengajuan_id'           => $pengajuan->id,
                'nama_lengkap_anggota'   => $request->nama_lengkap_anggota,
                'alamat_lengkap_anggota' => $request->alamat_lengkap_anggota,
                'nik_anggota'            => $request->nik_anggota,
                'file_kk_asli'           => $this->service->storeFile($request->file('file_kk_asli'), $folder),
                'file_ktp_asli'          => $this->service->storeFile($request->file('file_ktp_asli'), $folder),
                'file_sk_kematian'       => $this->service->storeFile($request->file('file_sk_kematian'), $folder),
            ]),

            'kk_baru' => FormKkBaru::create([
                'pengajuan_id'          => $pengajuan->id,
                'nama_kepala_keluarga'  => $request->nama_kepala_keluarga,
                'nomor_kk_lama'         => $request->nomor_kk_lama,
                'nama_ketua_rt'         => $request->nama_ketua_rt,
                'nama_ketua_rw'         => $request->nama_ketua_rw,
                'nama_lengkap_suami'    => $request->nama_lengkap_suami,
                'tempat_lahir_suami'    => $request->tempat_lahir_suami,
                'tanggal_lahir_suami'   => $request->tanggal_lahir_suami,
                'agama_suami'           => $request->agama_suami,
                'pekerjaan_suami'       => $request->pekerjaan_suami,
                'nama_lengkap_istri'    => $request->nama_lengkap_istri,
                'tempat_lahir_istri'    => $request->tempat_lahir_istri,
                'tanggal_lahir_istri'   => $request->tanggal_lahir_istri,
                'agama_istri'           => $request->agama_istri,
                'pekerjaan_istri'       => $request->pekerjaan_istri,
                'alamat_baru'           => $request->alamat_baru,
                'rt'                    => $request->kk_rt,
                'rw'                    => $request->kk_rw,
                'desa'                  => $request->kk_desa,
                'alasan_pembuatan'      => $request->alasan_pembuatan,
                'file_kk_lama'          => $this->service->storeFile($request->file('file_kk_lama'), $folder),
                'file_ktp_suami'        => $this->service->storeFile($request->file('file_ktp_suami'), $folder),
                'file_ktp_istri'        => $this->service->storeFile($request->file('file_ktp_istri'), $folder),
                'file_surat_domisili'   => $this->service->storeFile($request->file('file_surat_domisili'), $folder),
                'file_surat_nikah'      => $this->service->storeFile($request->file('file_surat_nikah'), $folder),
                'file_ijazah_suami'     => $this->service->storeFile($request->file('file_ijazah_suami'), $folder),
                'file_ijazah_istri'     => $this->service->storeFile($request->file('file_ijazah_istri'), $folder),
            ]),
        };
    }
}
