<?php

namespace App\Http\Requests\Desa;

class StorePengajuanDesaKiaRequest extends StorePengajuanDesaBaseRequest
{
    public function rules(): array
    {
        return array_merge($this->pemohonRules(), [
            'nama_lengkap_anak'    => ['required', 'string', 'max:255'],
            'tempat_lahir_anak'    => ['required', 'string', 'max:255'],
            'tanggal_lahir_anak'   => ['required', 'date'],
            'jenis_kelamin_anak'   => ['required', 'in:L,P'],
            'nama_kepala_keluarga' => ['required', 'string', 'max:255'],
            'agama'                => ['required', 'string', 'max:50'],
            'kewarganegaraan'      => ['required', 'string', 'max:50'],
            'file_akta_kelahiran'  => $this->fileRules(),
            'file_kk'              => $this->fileRules(),
            'file_surat_nikah'     => $this->fileRules(),
            'file_foto_anak'       => $this->fileRules(),
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->pemohonMessages(), [
            'nama_lengkap_anak.required'    => 'Nama lengkap anak wajib diisi.',
            'tempat_lahir_anak.required'    => 'Tempat lahir anak wajib diisi.',
            'tanggal_lahir_anak.required'   => 'Tanggal lahir anak wajib diisi.',
            'jenis_kelamin_anak.required'   => 'Jenis kelamin anak wajib dipilih.',
            'jenis_kelamin_anak.in'         => 'Jenis kelamin anak harus L atau P.',
            'nama_kepala_keluarga.required' => 'Nama kepala keluarga wajib diisi.',
            'agama.required'                => 'Agama wajib diisi.',
            'kewarganegaraan.required'      => 'Kewarganegaraan wajib diisi.',
            'file_akta_kelahiran.required'  => 'File akta kelahiran wajib diupload.',
            'file_akta_kelahiran.mimes'     => 'File akta kelahiran harus berformat jpg, png, atau pdf.',
            'file_akta_kelahiran.max'       => 'File akta kelahiran maksimal 5MB.',
            'file_kk.required'              => 'File KK wajib diupload.',
            'file_kk.mimes'                 => 'File KK harus berformat jpg, png, atau pdf.',
            'file_kk.max'                   => 'File KK maksimal 5MB.',
            'file_surat_nikah.required'     => 'File surat nikah wajib diupload.',
            'file_surat_nikah.mimes'        => 'File surat nikah harus berformat jpg, png, atau pdf.',
            'file_surat_nikah.max'          => 'File surat nikah maksimal 5MB.',
            'file_foto_anak.required'       => 'Foto anak wajib diupload.',
            'file_foto_anak.mimes'          => 'Foto anak harus berformat jpg, png, atau pdf.',
            'file_foto_anak.max'            => 'Foto anak maksimal 5MB.',
        ]);
    }
}
