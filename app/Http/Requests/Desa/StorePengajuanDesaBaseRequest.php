<?php

namespace App\Http\Requests\Desa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

abstract class StorePengajuanDesaBaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()?->isAdminDesa() ?? false;
    }

    /**
     * Rules untuk data identitas pemohon — wajib di semua jenis layanan.
     */
    protected function pemohonRules(): array
    {
        return [
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'nik'           => ['required', 'string', 'digits:16'],
            'no_whatsapp'   => ['required', 'string', 'max:20'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'pekerjaan'     => ['required', 'string', 'max:255'],
            'alamat'        => ['required', 'string', 'max:500'],
            'rt'            => ['required', 'string', 'max:10'],
            'rw'            => ['required', 'string', 'max:10'],
        ];
    }

    /**
     * Pesan error untuk data identitas pemohon.
     */
    protected function pemohonMessages(): array
    {
        return [
            'nama_lengkap.required'  => 'Nama lengkap pemohon wajib diisi.',
            'nik.required'           => 'NIK pemohon wajib diisi.',
            'nik.digits'             => 'NIK harus 16 digit.',
            'no_whatsapp.required'   => 'Nomor WhatsApp pemohon wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir pemohon wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin pemohon wajib dipilih.',
            'jenis_kelamin.in'       => 'Jenis kelamin harus L atau P.',
            'pekerjaan.required'     => 'Pekerjaan pemohon wajib diisi.',
            'alamat.required'        => 'Alamat pemohon wajib diisi.',
            'rt.required'            => 'RT wajib diisi.',
            'rw.required'            => 'RW wajib diisi.',
        ];
    }

    /**
     * Rules untuk file upload yang umum digunakan.
     */
    protected function fileRules(): array
    {
        return ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];
    }

    /**
     * Pesan error generik untuk file upload.
     */
    protected function fileMessages(string $label): array
    {
        return [
            'required' => "File {$label} wajib diupload.",
            'mimes'    => "File {$label} harus berformat jpg, png, atau pdf.",
            'max'      => "File {$label} maksimal 5MB.",
        ];
    }
}
