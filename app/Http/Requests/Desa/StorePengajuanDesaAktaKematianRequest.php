<?php

namespace App\Http\Requests\Desa;

class StorePengajuanDesaAktaKematianRequest extends StorePengajuanDesaBaseRequest
{
    public function rules(): array
    {
        return array_merge($this->pemohonRules(), [
            'nama_lengkap_anggota'   => ['required', 'string', 'max:255'],
            'alamat_lengkap_anggota' => ['required', 'string', 'max:500'],
            'nik_anggota'            => ['required', 'string', 'digits:16'],
            'file_kk_asli'           => $this->fileRules(),
            'file_ktp_asli'          => $this->fileRules(),
            'file_sk_kematian'       => $this->fileRules(),
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->pemohonMessages(), [
            'nama_lengkap_anggota.required'   => 'Nama lengkap anggota yang meninggal wajib diisi.',
            'alamat_lengkap_anggota.required' => 'Alamat lengkap anggota wajib diisi.',
            'nik_anggota.required'            => 'NIK anggota yang meninggal wajib diisi.',
            'nik_anggota.digits'              => 'NIK anggota harus 16 digit.',
            'file_kk_asli.required'           => 'File KK asli wajib diupload.',
            'file_kk_asli.mimes'              => 'File KK asli harus berformat jpg, png, atau pdf.',
            'file_kk_asli.max'                => 'File KK asli maksimal 5MB.',
            'file_ktp_asli.required'          => 'File KTP asli wajib diupload.',
            'file_ktp_asli.mimes'             => 'File KTP asli harus berformat jpg, png, atau pdf.',
            'file_ktp_asli.max'               => 'File KTP asli maksimal 5MB.',
            'file_sk_kematian.required'       => 'Surat keterangan kematian wajib diupload.',
            'file_sk_kematian.mimes'          => 'File surat kematian harus berformat jpg, png, atau pdf.',
            'file_sk_kematian.max'            => 'File surat kematian maksimal 5MB.',
        ]);
    }
}
