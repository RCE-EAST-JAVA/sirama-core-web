<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreKkPenambahanRequest extends FormRequest
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
            'no_whatsapp'              => ['required', 'string', 'max:20'],
            'nama_kepala_keluarga'     => ['required', 'string', 'max:255'],
            'nomor_kk'                 => ['required', 'string', 'max:20'],
            'alamat'                   => ['required', 'string', 'max:500'],
            'nama_dusun'               => ['required', 'string', 'max:255'],
            'rt'                       => ['required', 'string', 'max:10'],
            'rw'                       => ['required', 'string', 'max:10'],
            'nama_ketua_rt'            => ['required', 'string', 'max:255'],
            'nama_ketua_rw'            => ['required', 'string', 'max:255'],
            'nama_lengkap_tambahan'    => ['required', 'string', 'max:255'],
            'jenis_kelamin_tambahan'   => ['required', 'in:L,P'],
            'tempat_lahir_tambahan'    => ['required', 'string', 'max:255'],
            'tanggal_lahir_tambahan'   => ['required', 'date'],
            'status_hubungan'          => ['required', 'string', 'max:100'],
            'kelainan_fisik_mental'    => ['required', 'string', 'max:255'],
            'penyandang_cacat'         => ['required', 'string', 'max:255'],
            'agama'                    => ['required', 'string', 'max:50'],
            'nama_ibu_kandung'         => ['required', 'string', 'max:255'],
            'nik_ibu'                  => ['required', 'string', 'digits:16'],
            'nama_ayah_kandung'        => ['required', 'string', 'max:255'],
            'nik_ayah'                 => ['required', 'string', 'digits:16'],

            'file_kk_asli'             => $fileRules,
            'file_sk_lahir_akta'       => $fileRules,
            'file_ktp_suami_istri'     => $fileRules,
            'file_surat_nikah'         => $fileRules,
        ];
    }

    public function messages(): array
    {
        return [
            'no_whatsapp.required'            => 'Nomor WhatsApp wajib diisi.',
            'nama_kepala_keluarga.required'   => 'Nama kepala keluarga wajib diisi.',
            'nomor_kk.required'               => 'Nomor KK wajib diisi.',
            'alamat.required'                 => 'Alamat wajib diisi.',
            'nama_dusun.required'             => 'Nama dusun wajib diisi.',
            'rt.required'                     => 'RT wajib diisi.',
            'rw.required'                     => 'RW wajib diisi.',
            'nama_ketua_rt.required'          => 'Nama ketua RT wajib diisi.',
            'nama_ketua_rw.required'          => 'Nama ketua RW wajib diisi.',
            'nama_lengkap_tambahan.required'  => 'Nama lengkap anggota tambahan wajib diisi.',
            'jenis_kelamin_tambahan.required' => 'Jenis kelamin anggota tambahan wajib dipilih.',
            'jenis_kelamin_tambahan.in'       => 'Jenis kelamin harus L atau P.',
            'tempat_lahir_tambahan.required'  => 'Tempat lahir anggota tambahan wajib diisi.',
            'tanggal_lahir_tambahan.required' => 'Tanggal lahir anggota tambahan wajib diisi.',
            'status_hubungan.required'        => 'Status hubungan wajib diisi.',
            'kelainan_fisik_mental.required'  => 'Kelainan fisik/mental wajib diisi.',
            'penyandang_cacat.required'       => 'Keterangan penyandang cacat wajib diisi.',
            'agama.required'                  => 'Agama wajib diisi.',
            'nama_ibu_kandung.required'       => 'Nama ibu kandung wajib diisi.',
            'nik_ibu.required'                => 'NIK ibu wajib diisi.',
            'nik_ibu.digits'                  => 'NIK ibu harus 16 digit.',
            'nama_ayah_kandung.required'      => 'Nama ayah kandung wajib diisi.',
            'nik_ayah.required'               => 'NIK ayah wajib diisi.',
            'nik_ayah.digits'                 => 'NIK ayah harus 16 digit.',

            'file_kk_asli.required'           => 'File KK asli wajib diupload.',
            'file_kk_asli.mimes'              => 'File KK asli harus berformat jpg, png, atau pdf.',
            'file_kk_asli.max'                => 'File KK asli maksimal 5MB.',
            'file_sk_lahir_akta.required'     => 'File surat keterangan lahir/akta wajib diupload.',
            'file_sk_lahir_akta.mimes'        => 'File surat keterangan lahir/akta harus berformat jpg, png, atau pdf.',
            'file_sk_lahir_akta.max'          => 'File surat keterangan lahir/akta maksimal 5MB.',
            'file_ktp_suami_istri.required'   => 'File KTP suami/istri wajib diupload.',
            'file_ktp_suami_istri.mimes'      => 'File KTP suami/istri harus berformat jpg, png, atau pdf.',
            'file_ktp_suami_istri.max'        => 'File KTP suami/istri maksimal 5MB.',
            'file_surat_nikah.required'       => 'File surat nikah wajib diupload.',
            'file_surat_nikah.mimes'          => 'File surat nikah harus berformat jpg, png, atau pdf.',
            'file_surat_nikah.max'            => 'File surat nikah maksimal 5MB.',
        ];
    }
}
