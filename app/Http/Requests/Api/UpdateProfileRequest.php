<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['nullable', 'string', 'max:255'],
            'nik'                   => ['nullable', 'string', 'digits:16'],
            'no_whatsapp'           => ['nullable', 'string', 'max:20'],
            'tanggal_lahir'         => ['nullable', 'date'],
            'jenis_kelamin'         => ['nullable', 'in:L,P'],
            'pekerjaan'             => ['nullable', 'string', 'max:255'],
            'alamat'                => ['nullable', 'string', 'max:500'],
            'desa'                  => ['nullable', 'string', 'exists:desas,nama'],
            'rt'                    => ['nullable', 'string', 'max:10'],
            'rw'                    => ['nullable', 'string', 'max:10'],
            'foto_profil'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password'              => ['nullable', 'confirmed', Password::min(8)],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.digits'          => 'NIK harus 16 digit.',
            'jenis_kelamin.in'    => 'Jenis kelamin harus L atau P.',
            'foto_profil.image'   => 'Foto profil harus berupa gambar.',
            'foto_profil.mimes'   => 'Foto profil harus berformat jpg atau png.',
            'foto_profil.max'     => 'Foto profil maksimal 2MB.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ];
    }
}
