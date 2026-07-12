<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormAktaKematian extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_lengkap_anggota',
        'alamat_lengkap_anggota',
        'nik_anggota',
        'file_kk_asli',
        'file_ktp_asli',
        'file_sk_kematian',
    ];

    protected function casts(): array
    {
        return [
            'id'           => 'integer',
            'pengajuan_id' => 'integer',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function getFileDokumen(): array
    {
        return [
            'file_kk_asli'     => 'Kartu Keluarga Asli',
            'file_ktp_asli'    => 'KTP Asli',
            'file_sk_kematian' => 'Surat Keterangan Kematian',
        ];
    }

    public function getFileOcrTarget(): array
    {
        return ['file_kk_asli', 'file_ktp_asli', 'file_sk_kematian'];
    }
}
