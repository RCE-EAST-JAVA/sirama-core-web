<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTiga1Request extends FormRequest
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
            // Data diri pemohon — disimpan ke tabel pengajuans
            'nama_lengkap'   => [$required, 'string', 'max:255'],
            'nik'            => [$required, 'string', 'digits:16'],
            'no_whatsapp'    => [$required, 'string', 'max:20'],
            'tanggal_lahir'  => [$required, 'date'],
            'jenis_kelamin'  => [$required, 'in:L,P'],
            'pekerjaan'      => ['nullable', 'string', 'max:255'],
            'alamat'         => [$required, 'string', 'max:500'],
            'desa'           => [$required, 'string', 'max:255'],
            'rt'             => [$required, 'string', 'max:10'],
            'rw'             => [$required, 'string', 'max:10'],

            // Data spesifik form 3-in-1 — disimpan ke tabel form3_in1s
            'nama_anak'          => [$required, 'string', 'max:255'],
            'tanggal_lahir_anak' => [$required, 'date'],

            'file_sk_lahir'   => $fileRules,
            'file_kk'         => $fileRules,
            'file_ktp_ortu'   => $fileRules,
            'file_surat_nikah'=> $fileRules,
            'file_foto_anak'  => $fileRules,
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
            'file_sk_lahir.mimes'         => 'File surat keterangan lahir harus berformat jpg, png, atau pdf.',
            'file_sk_lahir.max'           => 'File surat keterangan lahir maksimal 5MB.',
            'file_kk.required'            => 'File KK wajib diupload.',
            'file_kk.mimes'               => 'File KK harus berformat jpg, png, atau pdf.',
            'file_kk.max'                 => 'File KK maksimal 5MB.',
            'file_ktp_ortu.required'      => 'File KTP orang tua wajib diupload.',
            'file_ktp_ortu.mimes'         => 'File KTP orang tua harus berformat jpg, png, atau pdf.',
            'file_ktp_ortu.max'           => 'File KTP orang tua maksimal 5MB.',
            'file_surat_nikah.required'   => 'File surat nikah wajib diupload.',
            'file_surat_nikah.mimes'      => 'File surat nikah harus berformat jpg, png, atau pdf.',
            'file_surat_nikah.max'        => 'File surat nikah maksimal 5MB.',
            'file_foto_anak.required'     => 'Foto anak wajib diupload.',
            'file_foto_anak.mimes'        => 'Foto anak harus berformat jpg atau png.',
            'file_foto_anak.max'          => 'Foto anak maksimal 5MB.',
        ];
    }
}
