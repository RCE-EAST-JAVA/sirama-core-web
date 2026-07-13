<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'no_whatsapp',
        'password',
        'role',
        'desa',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'alamat',
        'rt',
        'rw',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'password'      => 'hashed',
            'tanggal_lahir' => 'date',
        ];
    }

    // --- Role Helpers ---

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    public function isAdminDesa(): bool
    {
        return $this->role === 'admin_desa';
    }

    public function isAdminKecamatan(): bool
    {
        return $this->role === 'admin_kecamatan';
    }

    public function isAdminAplikasi(): bool
    {
        return $this->role === 'admin_aplikasi';
    }

    // --- Relationships ---

    public function pengajuans(): HasMany
    {
        return $this->hasMany(Pengajuan::class);
    }
}