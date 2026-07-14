<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class ProfileController extends Controller
{
    #[OA\Get(
        path: '/profile',
        summary: 'Get profil user yang sedang login',
        tags: ['Profile'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Data profil user',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'object', properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'nik', type: 'string'),
                            new OA\Property(property: 'no_whatsapp', type: 'string'),
                            new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date'),
                            new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                            new OA\Property(property: 'pekerjaan', type: 'string', nullable: true),
                            new OA\Property(property: 'alamat', type: 'string', nullable: true),
                            new OA\Property(property: 'desa', type: 'string', nullable: true),
                            new OA\Property(property: 'rt', type: 'string', nullable: true),
                            new OA\Property(property: 'rw', type: 'string', nullable: true),
                            new OA\Property(property: 'foto_profil', type: 'string', nullable: true),
                            new OA\Property(property: 'role', type: 'string'),
                        ])
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function show(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'data' => [
                'id'            => $user->id,
                'name'          => $user->name,
                'nik'           => $user->nik,
                'no_whatsapp'   => $user->no_whatsapp,
                'tanggal_lahir' => $user->tanggal_lahir?->format('Y-m-d'),
                'jenis_kelamin' => $user->jenis_kelamin,
                'pekerjaan'     => $user->pekerjaan,
                'alamat'        => $user->alamat,
                'desa'          => $user->desa,
                'rt'            => $user->rt,
                'rw'            => $user->rw,
                'foto_profil'   => $user->foto_profil
                    ? route('api.profile.foto')
                    : null,
                'role'          => $user->role,
            ],
        ]);
    }

    public function foto(): \Symfony\Component\HttpFoundation\Response
    {
        $user = Auth::user();

        if (!$user->foto_profil || !Storage::disk('local')->exists($user->foto_profil)) {
            abort(404, 'Foto profil tidak ditemukan');
        }

        $content  = Storage::disk('local')->get($user->foto_profil);
        $mimeType = Storage::disk('local')->mimeType($user->foto_profil);

        return response($content, 200, [
            'Content-Type'  => $mimeType,
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    #[OA\Post(
        path: '/profile',
        summary: 'Update profil user yang sedang login',
        description: 'Semua field bersifat opsional. Kirim hanya field yang ingin diupdate.',
        tags: ['Profile'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Budi Santoso'),
                        new OA\Property(property: 'nik', type: 'string', example: '3277010101900001'),
                        new OA\Property(property: 'no_whatsapp', type: 'string', example: '08123456789'),
                        new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', example: '1990-01-01'),
                        new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P']),
                        new OA\Property(property: 'pekerjaan', type: 'string', nullable: true),
                        new OA\Property(property: 'alamat', type: 'string'),
                        new OA\Property(property: 'desa', type: 'string'),
                        new OA\Property(property: 'rt', type: 'string'),
                        new OA\Property(property: 'rw', type: 'string'),
                        new OA\Property(property: 'foto_profil', type: 'string', format: 'binary', description: 'Foto profil (jpg/png, max 2MB)'),
                        new OA\Property(property: 'password', type: 'string', format: 'password'),
                        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Profil berhasil diupdate'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();

        $data = $request->only([
            'name', 'nik', 'no_whatsapp', 'tanggal_lahir',
            'jenis_kelamin', 'pekerjaan', 'alamat', 'desa', 'rt', 'rw',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('local')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('profil', 'local');
        }

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil berhasil diupdate.',
            'data'    => [
                'id'            => $user->id,
                'name'          => $user->name,
                'nik'           => $user->nik,
                'no_whatsapp'   => $user->no_whatsapp,
                'tanggal_lahir' => $user->tanggal_lahir?->format('Y-m-d'),
                'jenis_kelamin' => $user->jenis_kelamin,
                'pekerjaan'     => $user->pekerjaan,
                'alamat'        => $user->alamat,
                'desa'          => $user->desa,
                'rt'            => $user->rt,
                'rw'            => $user->rw,
                'foto_profil'   => $user->foto_profil
                    ? route('api.profile.foto')
                    : null,
                'role'          => $user->role,
            ],
        ]);
    }
}
