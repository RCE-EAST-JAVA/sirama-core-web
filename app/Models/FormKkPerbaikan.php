<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormKkPerbaikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'jenis_perbaikan_id',
        'nama_kepala_keluarga',
        'nomor_kk',
        'nama_anggota_yang_diperbaiki',
        'data_perbaikan',
        'file_pendukung',
    ];

    protected function casts(): array
    {
        return [
            'id'                 => 'integer',
            'pengajuan_id'       => 'integer',
            'jenis_perbaikan_id' => 'integer',
            'data_perbaikan'     => 'array', // key-value data yang diperbaiki
            'file_pendukung'     => 'array', // array path file pendukung
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function jenisPerbaikan(): BelongsTo
    {
        return $this->belongsTo(MasterJenisPerbaikanKk::class, 'jenis_perbaikan_id');
    }

    /**
     * Karena file_pendukung adalah JSON array,
     * kembalikan sebagai array path untuk dipreview satu per satu.
     */
    public function getFileDokumen(): array
    {
        $files = [];
        foreach ($this->file_pendukung ?? [] as $index => $path) {
            $files['file_pendukung_'.($index + 1)] = 'Dokumen Pendukung '.($index + 1);
        }
        return $files;
    }

    public function getFileOcrTarget(): array
    {
        // KK perbaikan: OCR pada semua file pendukung
        return array_keys($this->getFileDokumen());
    }
}

