<?php

namespace App\Http\Requests\Desa;

class StorePengajuanDesaKkPerbaikanRequest extends StorePengajuanDesaBaseRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->filled('data_perbaikan') && is_string($this->data_perbaikan)) {
            $decoded = json_decode($this->data_perbaikan, true);
            if (is_array($decoded)) {
                $this->merge(['data_perbaikan' => $decoded]);
            }
        }
    }

    public function rules(): array
    {
        return array_merge($this->pemohonRules(), [
            'jenis_perbaikan_id'           => ['required', 'integer', 'exists:master_jenis_perbaikan_kks,id'],
            'nama_kepala_keluarga'         => ['required', 'string', 'max:255'],
            'nomor_kk'                     => ['required', 'string', 'max:20'],
            'nama_anggota_yang_diperbaiki' => ['required', 'string', 'max:255'],
            'data_perbaikan'               => ['required', 'array'],
            'data_perbaikan.*'             => ['string'],
            'file_pendukung'               => ['required'],
            'file_pendukung.*'             => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->pemohonMessages(), [
            'jenis_perbaikan_id.required'             => 'Jenis perbaikan wajib dipilih.',
            'jenis_perbaikan_id.exists'               => 'Jenis perbaikan tidak valid.',
            'nama_kepala_keluarga.required'           => 'Nama kepala keluarga wajib diisi.',
            'nomor_kk.required'                       => 'Nomor KK wajib diisi.',
            'nama_anggota_yang_diperbaiki.required'   => 'Nama anggota yang diperbaiki wajib diisi.',
            'data_perbaikan.required'                 => 'Data perbaikan wajib diisi.',
            'data_perbaikan.array'                    => 'Data perbaikan harus berupa objek key-value.',
            'file_pendukung.required'                 => 'Minimal satu file pendukung wajib diupload.',
            'file_pendukung.*.file'                   => 'Setiap file pendukung harus berupa file.',
            'file_pendukung.*.mimes'                  => 'File pendukung harus berformat jpg, png, atau pdf.',
            'file_pendukung.*.max'                    => 'Setiap file pendukung maksimal 5MB.',
        ]);
    }
}
