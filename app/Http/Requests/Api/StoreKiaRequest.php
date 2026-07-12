<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreKiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWarga() ?? false;
    }

    public function rules(): array
    {
        $fileRules = $this->isMethod('POST')
            ? ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']
            : ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'];

        return [
            'no_whatsapp'          => ['required', 'string', 'max:20'],
            'nama_lengkap'         => ['required', 'string', 'max:255'],
            'tempat_lahir'         => ['required', 'string', 'max:255'],
            'tanggal_lahir'        => ['required', 'date'],
            'jenis_kelamin'        => ['required', 'in:L,P'],
            'nama_kepala_keluarga' => ['required', 'string', 'max:255'],
            'agama'                => ['required', 'string', 'max:50'],
            'kewarganegaraan'      => ['required', 'string', 'max:50'],

            'file_akta_kelahiran'  => $fileRules,
            'file_kk'              => $fileRules,
            'file_surat_nikah'     => $fileRules,
            'file_foto_anak'       => $fileRules,
        ];
    }

    public function messages(): array
    {
        return [
            'no_whatsapp.required'          => 'Nomor WhatsApp wajib diisi.',
            'nama_lengkap.required'         => 'Nama lengkap wajib diisi.',
            'tempat_lahir.required'         => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required'        => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required'        => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'              => 'Jenis kelamin harus L atau P.',
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
            'file_foto_anak.mimes'          => 'Foto anak harus berformat jpg atau png.',
            'file_foto_anak.max'            => 'Foto anak maksimal 5MB.',
        ];
    }
}
