<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Form3In1 extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_anak',
        'tanggal_lahir_anak',
        'file_sk_lahir',
        'file_kk',
        'file_ktp_ortu',
        'file_surat_nikah',
        'file_foto_anak',
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
            'file_sk_lahir'   => 'Surat Keterangan Lahir',
            'file_kk'         => 'Kartu Keluarga',
            'file_ktp_ortu'   => 'KTP Orang Tua',
            'file_surat_nikah'=> 'Surat/Buku Nikah',
            'file_foto_anak'  => 'Foto Anak',
        ];
    }

    public function getFileOcrTarget(): array
    {
        return ['file_sk_lahir', 'file_kk', 'file_ktp_ortu', 'file_surat_nikah'];
    }
}

