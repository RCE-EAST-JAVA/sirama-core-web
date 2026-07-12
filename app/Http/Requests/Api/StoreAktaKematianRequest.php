<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAktaKematianRequest extends FormRequest
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
            // Data diri pemohon — disimpan ke tabel pengajuans
            'nama_lengkap'           => [$required, 'string', 'max:255'],
            'nik'                    => [$required, 'string', 'digits:16'],
            'no_whatsapp'            => [$required, 'string', 'max:20'],
            'tanggal_lahir'          => [$required, 'date'],
            'jenis_kelamin'          => [$required, 'in:L,P'],
            'pekerjaan'              => ['nullable', 'string', 'max:255'],
            'alamat'                 => [$required, 'string', 'max:500'],
            'desa'                   => [$required, 'string', 'max:255'],
            'rt'                     => [$required, 'string', 'max:10'],
            'rw'                     => [$required, 'string', 'max:10'],

            // Data spesifik Akta Kematian (data anggota yang meninggal)
            'nama_lengkap_anggota'   => [$required, 'string', 'max:255'],
            'alamat_lengkap_anggota' => [$required, 'string', 'max:500'],
            'nik_anggota'            => [$required, 'string', 'digits:16'],

            'file_kk_asli'           => $fileRules,
            'file_ktp_asli'          => $fileRules,
            'file_sk_kematian'       => $fileRules,
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required'           => 'Nama lengkap wajib diisi.',
            'nik.required'                    => 'NIK wajib diisi.',
            'nik.digits'                      => 'NIK harus 16 digit.',
            'no_whatsapp.required'            => 'Nomor WhatsApp wajib diisi.',
            'tanggal_lahir.required'          => 'Tanggal lahir wajib diisi.',
            'jenis_kelamin.required'          => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'                => 'Jenis kelamin harus L atau P.',
            'alamat.required'                 => 'Alamat wajib diisi.',
            'desa.required'                   => 'Desa/Kelurahan wajib diisi.',
            'rt.required'                     => 'RT wajib diisi.',
            'rw.required'                     => 'RW wajib diisi.',

            'nama_lengkap_anggota.required'   => 'Nama lengkap anggota yang meninggal wajib diisi.',
            'alamat_lengkap_anggota.required' => 'Alamat lengkap anggota wajib diisi.',
            'nik_anggota.required'            => 'NIK anggota wajib diisi.',
            'nik_anggota.digits'              => 'NIK anggota harus 16 digit.',

            'file_kk_asli.required'           => 'File KK asli wajib diupload.',
            'file_kk_asli.mimes'              => 'File KK asli harus berformat jpg, png, atau pdf.',
            'file_kk_asli.max'                => 'File KK asli maksimal 5MB.',
            'file_ktp_asli.required'          => 'File KTP asli wajib diupload.',
            'file_ktp_asli.mimes'             => 'File KTP asli harus berformat jpg, png, atau pdf.',
            'file_ktp_asli.max'               => 'File KTP asli maksimal 5MB.',
            'file_sk_kematian.required'       => 'Surat keterangan kematian wajib diupload.',
            'file_sk_kematian.mimes'          => 'Surat keterangan kematian harus berformat jpg, png, atau pdf.',
            'file_sk_kematian.max'            => 'Surat keterangan kematian maksimal 5MB.',
        ];
    }
}
