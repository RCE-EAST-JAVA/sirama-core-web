<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DesaController extends Controller
{
    #[OA\Get(
        path: '/desas',
        summary: 'Ambil daftar desa yang tersedia',
        description: 'Endpoint publik — tidak perlu token. Digunakan untuk mengisi dropdown pilihan desa di frontend.',
        tags: ['Desa'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar desa',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'nama', type: 'string', example: 'Desa Sukosari Lor'),
                                    new OA\Property(property: 'kecamatan', type: 'string', example: 'Sukosari'),
                                ]
                            )
                        )
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $desas = Desa::orderBy('nama')->get(['id', 'nama', 'kecamatan']);

        return response()->json(['data' => $desas]);
    }
}
