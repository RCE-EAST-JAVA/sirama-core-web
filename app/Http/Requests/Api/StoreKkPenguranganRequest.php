<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreKkPenguranganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWarga() ?? false;
    }

    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'nullable';
        $fileRules = $this->isMethod('POST')
            ? ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']
            : ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];

        return [
            // Data spesifik KK Pengurangan — disimpan ke tabel form_kk_pengurangans
            'alasan_pengurangan'     => [$required, 'string', 'max:500'],
            'nama_lengkap_anggota'   => [$required, 'string', 'max:255'],
            'alamat_lengkap_anggota' => [$required, 'string', 'max:500'],
            'nik_anggota'            => [$required, 'string', 'digits:16'],

            'file_kk_asli'           => $fileRules,
            'file_ktp_asli'          => $fileRules,
            'file_sk_pindah_mati'    => $fileRules,
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_pengurangan.required'     => 'Alasan pengurangan wajib diisi.',
            'nama_lengkap_anggota.required'   => 'Nama lengkap anggota wajib diisi.',
            'alamat_lengkap_anggota.required' => 'Alamat lengkap anggota wajib diisi.',
            'nik_anggota.required'            => 'NIK anggota wajib diisi.',
            'nik_anggota.digits'              => 'NIK anggota harus 16 digit.',

            'file_kk_asli.required'           => 'File KK asli wajib diupload.',
            'file_kk_asli.mimes'              => 'File KK asli harus berformat jpg, png, atau pdf.',
            'file_kk_asli.max'                => 'File KK asli maksimal 5MB.',
            'file_ktp_asli.required'          => 'File KTP asli wajib diupload.',
            'file_ktp_asli.mimes'             => 'File KTP asli harus berformat jpg, png, atau pdf.',
            'file_ktp_asli.max'               => 'File KTP asli maksimal 5MB.',
            'file_sk_pindah_mati.required'    => 'File surat keterangan pindah/meninggal wajib diupload.',
            'file_sk_pindah_mati.mimes'       => 'File surat keterangan pindah/meninggal harus berformat jpg, png, atau pdf.',
            'file_sk_pindah_mati.max'         => 'File surat keterangan pindah/meninggal maksimal 5MB.',
        ];
    }
}
