<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Form3In1 extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pengajuan_id',
        'nama_lengkap_pemohon',
        'desa',
        'alamat_lengkap',
        'nama_anak',
        'tanggal_lahir_anak',
        'file_sk_lahir',
        'file_kk',
        'file_ktp_ortu',
        'file_surat_nikah',
        'file_foto_anak',
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
            'tanggal_lahir_anak' => 'date',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
