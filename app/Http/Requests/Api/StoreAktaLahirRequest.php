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
            // Data diri pemohon — nullable, fallback ke profil user jika kosong
            'nama_lengkap'       => ['nullable', 'string', 'max:255'],
            'nik'                => ['nullable', 'string', 'digits:16'],
            'no_whatsapp'        => ['nullable', 'string', 'max:20'],
            'tanggal_lahir'      => ['nullable', 'date'],
            'jenis_kelamin'      => ['nullable', 'in:L,P'],
            'pekerjaan'          => ['nullable', 'string', 'max:255'],
            'alamat'             => ['nullable', 'string', 'max:500'],
            'desa'               => ['nullable', 'string', 'max:255'],
            'rt'                 => ['nullable', 'string', 'max:10'],
            'rw'                 => ['nullable', 'string', 'max:10'],

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
            'nama_lengkap.required'       => 'Nama lengkap wajib diisi.',
            'nik.required'                => 'NIK wajib diisi.',
            'nik.digits'                  => 'NIK harus 16 digit.',
            'no_whatsapp.required'        => 'Nomor WhatsApp wajib diisi.',
            'tanggal_lahir.required'      => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required'      => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'            => 'Jenis kelamin harus L atau P.',
            'alamat.required'             => 'Alamat wajib diisi.',
            'desa.required'               => 'Desa/Kelurahan wajib diisi.',
            'rt.required'                 => 'RT wajib diisi.',
            'rw.required'                 => 'RW wajib diisi.',

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
