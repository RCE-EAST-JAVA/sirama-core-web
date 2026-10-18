<?php

namespace App\Http\Requests\Desa;

class StorePengajuanDesaKkBaruRequest extends StorePengajuanDesaBaseRequest
{
    public function rules(): array
    {
        return array_merge($this->pemohonRules(), [
            'nama_kepala_keluarga'   => ['required', 'string', 'max:255'],
            'nomor_kk_lama'          => ['required', 'string', 'max:20'],
            'nama_ketua_rt'          => ['required', 'string', 'max:255'],
            'nama_ketua_rw'          => ['required', 'string', 'max:255'],
            'nama_lengkap_suami'     => ['required', 'string', 'max:255'],
            'tempat_lahir_suami'     => ['required', 'string', 'max:255'],
            'tanggal_lahir_suami'    => ['required', 'date'],
            'agama_suami'            => ['required', 'string', 'max:50'],
            'pekerjaan_suami'        => ['required', 'string', 'max:255'],
            'nama_lengkap_istri'     => ['required', 'string', 'max:255'],
            'tempat_lahir_istri'     => ['required', 'string', 'max:255'],
            'tanggal_lahir_istri'    => ['required', 'date'],
            'agama_istri'            => ['required', 'string', 'max:50'],
            'pekerjaan_istri'        => ['required', 'string', 'max:255'],
            'alamat_baru'            => ['required', 'string', 'max:500'],
            'kk_rt'                  => ['required', 'string', 'max:10'],
            'kk_rw'                  => ['required', 'string', 'max:10'],
            'kk_desa'                => ['required', 'string', 'max:255'],
            'alasan_pembuatan'       => ['required', 'string', 'max:500'],
            'file_kk_lama'           => $this->fileRules(),
            'file_ktp_suami'         => $this->fileRules(),
            'file_ktp_istri'         => $this->fileRules(),
            'file_surat_domisili'    => $this->fileRules(),
            'file_surat_nikah'       => $this->fileRules(),
            'file_ijazah_suami'      => $this->fileRules(),
            'file_ijazah_istri'      => $this->fileRules(),
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->pemohonMessages(), [
            'nama_kepala_keluarga.required' => 'Nama kepala keluarga wajib diisi.',
            'nomor_kk_lama.required'        => 'Nomor KK lama wajib diisi.',
            'nama_ketua_rt.required'        => 'Nama ketua RT wajib diisi.',
            'nama_ketua_rw.required'        => 'Nama ketua RW wajib diisi.',
            'nama_lengkap_suami.required'   => 'Nama lengkap suami wajib diisi.',
            'tempat_lahir_suami.required'   => 'Tempat lahir suami wajib diisi.',
            'tanggal_lahir_suami.required'  => 'Tanggal lahir suami wajib diisi.',
            'agama_suami.required'          => 'Agama suami wajib diisi.',
            'pekerjaan_suami.required'      => 'Pekerjaan suami wajib diisi.',
            'nama_lengkap_istri.required'   => 'Nama lengkap istri wajib diisi.',
            'tempat_lahir_istri.required'   => 'Tempat lahir istri wajib diisi.',
            'tanggal_lahir_istri.required'  => 'Tanggal lahir istri wajib diisi.',
            'agama_istri.required'          => 'Agama istri wajib diisi.',
            'pekerjaan_istri.required'      => 'Pekerjaan istri wajib diisi.',
            'alamat_baru.required'          => 'Alamat baru wajib diisi.',
            'kk_rt.required'                => 'RT wajib diisi.',
            'kk_rw.required'                => 'RW wajib diisi.',
            'kk_desa.required'              => 'Desa wajib diisi.',
            'alasan_pembuatan.required'     => 'Alasan pembuatan KK baru wajib diisi.',
            'file_kk_lama.required'         => 'File KK lama wajib diupload.',
            'file_kk_lama.mimes'            => 'File KK lama harus berformat jpg, png, atau pdf.',
            'file_kk_lama.max'              => 'File KK lama maksimal 5MB.',
            'file_ktp_suami.required'       => 'File KTP suami wajib diupload.',
            'file_ktp_suami.mimes'          => 'File KTP suami harus berformat jpg, png, atau pdf.',
            'file_ktp_suami.max'            => 'File KTP suami maksimal 5MB.',
            'file_ktp_istri.required'       => 'File KTP istri wajib diupload.',
            'file_ktp_istri.mimes'          => 'File KTP istri harus berformat jpg, png, atau pdf.',
            'file_ktp_istri.max'            => 'File KTP istri maksimal 5MB.',
            'file_surat_domisili.required'  => 'File surat domisili wajib diupload.',
            'file_surat_domisili.mimes'     => 'File surat domisili harus berformat jpg, png, atau pdf.',
            'file_surat_domisili.max'       => 'File surat domisili maksimal 5MB.',
            'file_surat_nikah.required'     => 'File surat nikah wajib diupload.',
            'file_surat_nikah.mimes'        => 'File surat nikah harus berformat jpg, png, atau pdf.',
            'file_surat_nikah.max'          => 'File surat nikah maksimal 5MB.',
            'file_ijazah_suami.required'    => 'File ijazah suami wajib diupload.',
            'file_ijazah_suami.mimes'       => 'File ijazah suami harus berformat jpg, png, atau pdf.',
            'file_ijazah_suami.max'         => 'File ijazah suami maksimal 5MB.',
            'file_ijazah_istri.required'    => 'File ijazah istri wajib diupload.',
            'file_ijazah_istri.mimes'       => 'File ijazah istri harus berformat jpg, png, atau pdf.',
            'file_ijazah_istri.max'         => 'File ijazah istri maksimal 5MB.',
        ]);
    }
}
