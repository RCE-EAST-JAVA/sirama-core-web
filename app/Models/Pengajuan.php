<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_layanan',
        'status',
        'lokasi_dokumen',
        'no_whatsapp',
    ];

    protected function casts(): array
    {
        return [
            'id'      => 'integer',
            'user_id' => 'integer',
        ];
    }

    // --- Relationships ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function riwayatStatuses(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class);
    }

    public function ocrResults(): HasMany
    {
        return $this->hasMany(OcrResult::class);
    }

    public function formKia(): HasOne
    {
        return $this->hasOne(FormKia::class);
    }

    public function form3In1(): HasOne
    {
        return $this->hasOne(Form3In1::class);
    }

    public function formKkPenambahan(): HasOne
    {
        return $this->hasOne(FormKkPenambahan::class);
    }

    public function formKkPengurangan(): HasOne
    {
        return $this->hasOne(FormKkPengurangan::class);
    }

    public function formKkPerbaikan(): HasOne
    {
        return $this->hasOne(FormKkPerbaikan::class);
    }

    // --- Helpers ---

    public function getFormDetail(): ?Model
    {
        return match ($this->jenis_layanan) {
            'kia'            => $this->formKia,
            '3_in_1'         => $this->form3In1,
            'kk_penambahan'  => $this->formKkPenambahan,
            'kk_pengurangan' => $this->formKkPengurangan,
            'kk_perbaikan'   => $this->formKkPerbaikan,
            default          => null,
        };
    }

    public function getLabelStatus(): string
    {
        return match ($this->status) {
            'berkas_diterima'     => 'Berkas Diterima',
            'ditolak_desa'        => 'Ditolak Desa',
            'diverifikasi_desa'   => 'Diverifikasi Desa',
            'ditolak_kecamatan'   => 'Ditolak Kecamatan',
            'diproses_kecamatan'  => 'Diproses Kecamatan',
            'selesai'             => 'Selesai',
            default               => $this->status,
        };
    }

    public function getLabelJenisLayanan(): string
    {
        return match ($this->jenis_layanan) {
            'kia'            => 'KIA (Kartu Identitas Anak)',
            '3_in_1'         => '3 in 1 (Akta + KK + KIA)',
            'kk_penambahan'  => 'Penambahan Anggota KK',
            'kk_pengurangan' => 'Pengurangan Anggota KK',
            'kk_perbaikan'   => 'Perbaikan Data KK',
            'akta_kelahiran' => 'Akta Kelahiran',
            'akta_kematian'  => 'Akta Kematian',
            default          => $this->jenis_layanan,
        };
    }
}

