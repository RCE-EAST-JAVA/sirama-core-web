<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $formDetail = $this->whenLoaded('formKia',
            fn() => $this->formKia,
            fn() => $this->whenLoaded('form3In1',
                fn() => $this->form3In1,
                fn() => $this->whenLoaded('formKkPenambahan',
                    fn() => $this->formKkPenambahan,
                    fn() => $this->whenLoaded('formKkPengurangan',
                        fn() => $this->formKkPengurangan,
                        fn() => $this->whenLoaded('formKkPerbaikan',
                            fn() => $this->formKkPerbaikan,
                            null
                        )
                    )
                )
            )
        );

        return [
            'id'             => $this->id,
            'jenis_layanan'  => $this->jenis_layanan,
            'label_layanan'  => $this->getLabelJenisLayanan(),
            'status'         => $this->status,
            'label_status'   => $this->getLabelStatus(),
            'no_whatsapp'    => $this->no_whatsapp,
            'lokasi_dokumen' => $this->lokasi_dokumen,
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),

            'user'    => new UserResource($this->whenLoaded('user')),

            'form_detail' => $this->when(
                $this->relationLoaded('formKia') ||
                $this->relationLoaded('form3In1') ||
                $this->relationLoaded('formKkPenambahan') ||
                $this->relationLoaded('formKkPengurangan') ||
                $this->relationLoaded('formKkPerbaikan'),
                fn() => $this->getFormDetail()
            ),

            'riwayat_statuses' => $this->whenLoaded('riwayatStatuses', function () {
                return $this->riwayatStatuses->sortByDesc('created_at')->values()->map(fn($r) => [
                    'status'  => $r->status_riwayat,
                    'catatan' => $r->catatan,
                    'waktu'   => $r->created_at->toIso8601String(),
                ]);
            }),
        ];
    }
}
