<?php

namespace App\Http\Requests\Desa;

class StorePengajuanDesaAktaLahirRequest extends StorePengajuanDesaBaseRequest
{
    public function rules(): array
    {
        return array_merge($this->pemohonRules(), [
            'nama_anak'          => ['required', 'string', 'max:255'],
            'tanggal_lahir_anak' => ['required', 'date'],
            'file_sk_lahir'      => $this->fileRules(),
            'file_kk'            => $this->fileRules(),
            'file_ktp_ayah'      => $this->fileRules(),
            'file_ktp_ibu'       => $this->fileRules(),
            'file_surat_nikah'   => $this->fileRules(),
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->pemohonMessages(), [
            'nama_anak.required'          => 'Nama anak wajib diisi.',
            'tanggal_lahir_anak.required' => 'Tanggal lahir anak wajib diisi.',
            'file_sk_lahir.required'      => 'Surat keterangan lahir wajib diupload.',
            'file_sk_lahir.mimes'         => 'File surat keterangan lahir harus berformat jpg, png, atau pdf.',
            'file_sk_lahir.max'           => 'File surat keterangan lahir maksimal 5MB.',
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
        ]);
    }
}
