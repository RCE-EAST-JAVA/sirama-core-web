<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisPerbaikanKk;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class JenisPerbaikanKkController extends Controller
{
    #[OA\Get(
        path: '/jenis-perbaikan-kk',
        summary: 'Ambil daftar jenis perbaikan KK',
        description: 'Digunakan untuk mengisi dropdown pilihan jenis perbaikan pada form pengajuan KK Perbaikan.',
        tags: ['Master Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Daftar jenis perbaikan KK',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'nama_perbaikan', type: 'string', example: 'Perbaikan Nama'),
                                    new OA\Property(property: 'deskripsi', type: 'string', example: 'Perbaikan kesalahan penulisan nama'),
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
        $jenisPerbaikan = MasterJenisPerbaikanKk::orderBy('nama_perbaikan')
            ->get(['id', 'nama_perbaikan', 'deskripsi']);

        return response()->json(['data' => $jenisPerbaikan]);
    }
}
