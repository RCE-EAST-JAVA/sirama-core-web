<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormAktaLahir extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_anak',
        'tanggal_lahir_anak',
        'file_sk_lahir',
        'file_kk',
        'file_ktp_ayah',
        'file_ktp_ibu',
        'file_surat_nikah',
    ];

    protected function casts(): array
    {
        return [
            'id'                 => 'integer',
            'pengajuan_id'       => 'integer',
            'tanggal_lahir_anak' => 'date',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function getFileDokumen(): array
    {
        return [
            'file_sk_lahir'    => 'Surat Keterangan Lahir',
            'file_kk'          => 'Kartu Keluarga',
            'file_ktp_ayah'    => 'KTP Ayah',
            'file_ktp_ibu'     => 'KTP Ibu',
            'file_surat_nikah' => 'Surat/Buku Nikah',
        ];
    }

    public function getFileOcrTarget(): array
    {
        return ['file_sk_lahir', 'file_kk', 'file_surat_nikah'];
    }
}
