<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterJenisPerbaikanKk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perbaikan',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    public function formKkPerbaikans(): HasMany
    {
        return $this->hasMany(FormKkPerbaikan::class, 'jenis_perbaikan_id');
    }
}

