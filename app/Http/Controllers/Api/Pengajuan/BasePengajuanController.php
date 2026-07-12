<?php

namespace App\Http\Controllers\Api\Pengajuan;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengajuanResource;
use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Pengajuan', description: 'Endpoint pengajuan layanan kependudukan')]
abstract class BasePengajuanController extends Controller
{
    #[OA\Get(
        path: '/pengajuan',
        summary: 'Daftar pengajuan milik warga',
        description: 'Mengembalikan semua pengajuan milik warga yang sedang login, diurutkan terbaru. Mendukung filter berdasarkan status.',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'status',
                in: 'query',
                required: false,
                description: 'Filter berdasarkan status pengajuan',
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['berkas_diterima', 'ditolak_desa', 'diverifikasi_desa', 'ditolak_kecamatan', 'diproses_kecamatan', 'selesai']
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar pengajuan dengan pagination',
                content: new OA\JsonContent(ref: '#/components/schemas/PengajuanListResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $pengajuans = Pengajuan::with('user')
            ->where('user_id', Auth::id())
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => PengajuanResource::collection($pengajuans),
            'meta' => [
                'current_page' => $pengajuans->currentPage(),
                'last_page'    => $pengajuans->lastPage(),
                'total'        => $pengajuans->total(),
            ],
        ]);
    }

    #[OA\Get(
        path: '/pengajuan/{id}',
        summary: 'Detail satu pengajuan',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Detail pengajuan', content: new OA\JsonContent(ref: '#/components/schemas/PengajuanResponse')),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function show(Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        $pengajuan->load([
            'user',
            'formKia',
            'form3In1',
            'formKkPenambahan',
            'formKkPengurangan',
            'formKkPerbaikan',
            'riwayatStatuses',
        ]);

        return response()->json([
            'data' => new PengajuanResource($pengajuan),
        ]);
    }

    #[OA\Get(
        path: '/pengajuan/{id}/status',
        summary: 'Cek status pengajuan (polling)',
        tags: ['Pengajuan'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Status terkini pengajuan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'diverifikasi_desa'),
                        new OA\Property(property: 'label_status', type: 'string', example: 'Diverifikasi Desa'),
                        new OA\Property(property: 'lokasi_dokumen', type: 'string', nullable: true),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not Found'),
        ]
    )]
    public function status(Pengajuan $pengajuan): JsonResponse
    {
        $this->authorizeWarga($pengajuan);

        return response()->json([
            'status'         => $pengajuan->status,
            'label_status'   => $pengajuan->getLabelStatus(),
            'lokasi_dokumen' => $pengajuan->lokasi_dokumen,
        ]);
    }

    /**
     * Pastikan pengajuan milik user yang sedang login.
     */
    protected function authorizeWarga(Pengajuan $pengajuan): void
    {
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Simpan file ke storage dan kembalikan path-nya.
     */
    protected function storeFile(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        return $file->store($folder, 'public');
    }
}
