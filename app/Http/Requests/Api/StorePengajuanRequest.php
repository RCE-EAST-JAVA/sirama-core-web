<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isWarga() ?? false;
    }

    public function rules(): array
    {
        return [
            'jenis_layanan' => ['required', 'in:kia,3_in_1,kk_penambahan,kk_pengurangan,kk_perbaikan,akta_kelahiran,akta_kematian'],
            'no_whatsapp'   => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_layanan.required' => 'Jenis layanan wajib dipilih.',
            'jenis_layanan.in'       => 'Jenis layanan tidak valid.',
            'no_whatsapp.required'   => 'Nomor WhatsApp wajib diisi.',
        ];
    }
}
