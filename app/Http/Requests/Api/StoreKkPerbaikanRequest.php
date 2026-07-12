<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreKkPerbaikanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWarga() ?? false;
    }

    public function rules(): array
    {
        return [
            'no_whatsapp'                  => ['required', 'string', 'max:20'],
            'jenis_perbaikan_id'           => ['required', 'integer', 'exists:master_jenis_perbaikan_kks,id'],
            'nama_kepala_keluarga'         => ['required', 'string', 'max:255'],
            'nomor_kk'                     => ['required', 'string', 'max:20'],
            'nama_anggota_yang_diperbaiki' => ['required', 'string', 'max:255'],
            'data_perbaikan'               => ['required', 'array'],
            'data_perbaikan.*'             => ['string'],

            // file_pendukung adalah array file (bisa lebih dari satu)
            'file_pendukung'               => [$this->isMethod('POST') ? 'required' : 'nullable', 'array', 'min:1'],
            'file_pendukung.*'             => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'no_whatsapp.required'                    => 'Nomor WhatsApp wajib diisi.',
            'jenis_perbaikan_id.required'             => 'Jenis perbaikan wajib dipilih.',
            'jenis_perbaikan_id.exists'               => 'Jenis perbaikan tidak valid.',
            'nama_kepala_keluarga.required'           => 'Nama kepala keluarga wajib diisi.',
            'nomor_kk.required'                       => 'Nomor KK wajib diisi.',
            'nama_anggota_yang_diperbaiki.required'   => 'Nama anggota yang diperbaiki wajib diisi.',
            'data_perbaikan.required'                 => 'Data perbaikan wajib diisi.',
            'data_perbaikan.array'                    => 'Data perbaikan harus berupa objek key-value.',
            'file_pendukung.required'                 => 'Minimal satu file pendukung wajib diupload.',
            'file_pendukung.array'                    => 'File pendukung harus berupa array.',
            'file_pendukung.*.file'                   => 'Setiap file pendukung harus berupa file.',
            'file_pendukung.*.mimes'                  => 'File pendukung harus berformat jpg, png, atau pdf.',
            'file_pendukung.*.max'                    => 'Setiap file pendukung maksimal 5MB.',
        ];
    }
}
