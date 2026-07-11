<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OcrResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'field_dokumen',
        'hasil_ocr',
        'confidence_score',
    ];

    protected function casts(): array
    {
        return [
            'id'               => 'integer',
            'pengajuan_id'     => 'integer',
            'hasil_ocr'        => 'array',
            'confidence_score' => 'decimal:4',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
