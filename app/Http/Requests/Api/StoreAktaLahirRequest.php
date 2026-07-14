<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAktaLahirRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWarga() ?? false;
    }

    public function rules(): array
    {
        $required  = $this->isMethod('POST') ? 'required' : 'nullable';
        $fileRules = $this->isMethod('POST')
            ? ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']
            : ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];

        return [
            // Data spesifik Akta Lahir — disimpan ke tabel form_akta_lahirs
            'nama_anak'          => [$required, 'string', 'max:255'],
            'tanggal_lahir_anak' => [$required, 'date'],

            'file_sk_lahir'      => $fileRules,
            'file_kk'            => $fileRules,
            'file_ktp_ayah'      => $fileRules,
            'file_ktp_ibu'       => $fileRules,
            'file_surat_nikah'   => $fileRules,
        ];
    }

    public function messages(): array
    {
        return [
            'nama_anak.required'          => 'Nama anak wajib diisi.',
            'tanggal_lahir_anak.required' => 'Tanggal lahir anak wajib diisi.',

            'file_sk_lahir.required'      => 'Surat keterangan lahir wajib diupload.',
            'file_sk_lahir.mimes'         => 'Surat keterangan lahir harus berformat jpg, png, atau pdf.',
            'file_sk_lahir.max'           => 'Surat keterangan lahir maksimal 5MB.',
            'file_kk.required'            => 'File KK wajib diupload.',
            'file_kk.mimes'               => 'File KK harus berformat jpg, png, atau pdf.',
            'file_kk.max'                 => 'File KK maksimal 5MB.',
            'file_ktp_ayah.required'      => 'File KTP ayah wajib diupload.',
            'file_ktp_ayah.mimes'         => 'File KTP ayah harus berformat jpg, png, atau pdf.',
            'file_ktp_ayah.max'           => 'File KTP ayah maksimal 5MB.',
            'file_ktp_ibu.required'       => 'File KTP ibu wajib diupload.',
            'file_ktp_ibu.mimes'          => 'File KTP ibu harus berformat jpg, png, atau pdf.',
            'file_ktp_ibu.max'            => 'File KTP ibu maksimal 5MB.',
            'file_surat_nikah.required'   => 'File surat nikah wajib diupload.',
            'file_surat_nikah.mimes'      => 'File surat nikah harus berformat jpg, png, atau pdf.',
            'file_surat_nikah.max'        => 'File surat nikah maksimal 5MB.',
        ];
    }
}
