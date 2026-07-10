<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormKkPerbaikan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pengajuan_id',
        'jenis_perbaikan_id',
        'nama_kepala_keluarga',
        'nomor_kk',
        'nama_anggota_yang_diperbaiki',
        'data_perbaikan',
        'file_pendukung',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'pengajuan_id' => 'integer',
            'jenis_perbaikan_id' => 'integer',
            'data_perbaikan' => 'array',
            'file_pendukung' => 'array',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function jenisPerbaikan(): BelongsTo
    {
        return $this->belongsTo(MasterJenisPerbaikanKk::class);
    }
}
