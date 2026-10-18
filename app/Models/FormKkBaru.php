<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormKkBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_kepala_keluarga',
        'nomor_kk_lama',
        'nama_ketua_rt',
        'nama_ketua_rw',
        'nama_lengkap_suami',
        'tempat_lahir_suami',
        'tanggal_lahir_suami',
        'agama_suami',
        'pekerjaan_suami',
        'nama_lengkap_istri',
        'tempat_lahir_istri',
        'tanggal_lahir_istri',
        'agama_istri',
        'pekerjaan_istri',
        'alamat_baru',
        'rt',
        'rw',
        'desa',
        'alasan_pembuatan',
        'file_kk_lama',
        'file_ktp_suami',
        'file_ktp_istri',
        'file_surat_domisili',
        'file_surat_nikah',
        'file_ijazah_suami',
        'file_ijazah_istri',
    ];

    protected function casts(): array
    {
        return [
            'id'                   => 'integer',
            'pengajuan_id'         => 'integer',
            'tanggal_lahir_suami'  => 'date',
            'tanggal_lahir_istri'  => 'date',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function getFileDokumen(): array
    {
        return [
            'file_kk_lama'         => 'KK Lama',
            'file_ktp_suami'       => 'KTP Suami',
            'file_ktp_istri'       => 'KTP Istri',
            'file_surat_domisili'  => 'Surat Domisili',
            'file_surat_nikah'     => 'Surat/Buku Nikah',
            'file_ijazah_suami'    => 'Ijazah Terakhir Suami',
            'file_ijazah_istri'    => 'Ijazah Terakhir Istri',
        ];
    }

    public function getFileOcrTarget(): array
    {
        return [
            'file_kk_lama',
            'file_ktp_suami',
            'file_ktp_istri',
            'file_surat_domisili',
            'file_surat_nikah',
            'file_ijazah_suami',
            'file_ijazah_istri',
        ];
    }
}
