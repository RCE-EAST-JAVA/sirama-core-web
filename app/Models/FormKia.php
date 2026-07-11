<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormKia extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'nama_kepala_keluarga',
        'agama',
        'kewarganegaraan',
        'file_akta_kelahiran',
        'file_kk',
        'file_surat_nikah',
        'file_foto_anak',
    ];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'pengajuan_id'  => 'integer',
            'tanggal_lahir' => 'date',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    /**
     * Daftar field file dokumen yang perlu OCR.
     */
    public function getFileDokumen(): array
    {
        return [
            'file_akta_kelahiran' => 'Akta Kelahiran',
            'file_kk'             => 'Kartu Keluarga',
            'file_surat_nikah'    => 'Surat/Buku Nikah',
            'file_foto_anak'      => 'Foto Anak',
        ];
    }

    /**
     * Field yang perlu diproses OCR (bukan foto biasa).
     */
    public function getFileOcrTarget(): array
    {
        return ['file_akta_kelahiran', 'file_kk', 'file_surat_nikah'];
    }
}

