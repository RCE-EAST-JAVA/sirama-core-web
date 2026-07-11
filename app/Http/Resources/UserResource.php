<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nik'         => $this->nik,
            'name'        => $this->name,
            'no_whatsapp' => $this->no_whatsapp,
            'role'        => $this->role,
            'desa'        => $this->desa,
        ];
    }
}
