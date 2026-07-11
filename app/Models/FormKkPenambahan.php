<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormKkPenambahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_kepala_keluarga',
        'nomor_kk',
        'alamat',
        'nama_dusun',
        'rt',
        'rw',
        'nama_ketua_rt',
        'nama_ketua_rw',
        'nama_lengkap_tambahan',
        'jenis_kelamin_tambahan',
        'tempat_lahir_tambahan',
        'tanggal_lahir_tambahan',
        'status_hubungan',
        'kelainan_fisik_mental',
        'penyandang_cacat',
        'agama',
        'nama_ibu_kandung',
        'nik_ibu',
        'nama_ayah_kandung',
        'nik_ayah',
        'file_kk_asli',
        'file_sk_lahir_akta',
        'file_ktp_suami_istri',
        'file_surat_nikah',
    ];

    protected function casts(): array
    {
        return [
            'id'                     => 'integer',
            'pengajuan_id'           => 'integer',
            'tanggal_lahir_tambahan' => 'date',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function getFileDokumen(): array
    {
        return [
            'file_kk_asli'        => 'KK Asli',
            'file_sk_lahir_akta'  => 'Surat Keterangan Lahir / Akta',
            'file_ktp_suami_istri'=> 'KTP Suami/Istri',
            'file_surat_nikah'    => 'Surat/Buku Nikah',
        ];
    }

    public function getFileOcrTarget(): array
    {
        return ['file_kk_asli', 'file_sk_lahir_akta', 'file_ktp_suami_istri', 'file_surat_nikah'];
    }
}

