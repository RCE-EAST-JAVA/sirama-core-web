<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/auth/register',
        summary: 'Registrasi warga baru',
        description: 'Membuat akun warga baru dengan NIK sebagai username. Field profil (tanggal_lahir, jenis_kelamin, dll) bersifat opsional dan bisa dilengkapi nanti via update profile.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nik', 'name', 'no_whatsapp', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'nik', type: 'string', minLength: 16, maxLength: 16, example: '3277010101900001', description: '16 digit NIK'),
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Budi Santoso'),
                    new OA\Property(property: 'no_whatsapp', type: 'string', maxLength: 20, example: '08123456789'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, example: 'password'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password'),
                    new OA\Property(property: 'tanggal_lahir', type: 'string', format: 'date', nullable: true, example: '1990-01-01'),
                    new OA\Property(property: 'jenis_kelamin', type: 'string', enum: ['L', 'P'], nullable: true),
                    new OA\Property(property: 'pekerjaan', type: 'string', nullable: true, example: 'Wiraswasta'),
                    new OA\Property(property: 'alamat', type: 'string', nullable: true, example: 'Jl. Merdeka No. 1'),
                    new OA\Property(property: 'desa', type: 'string', nullable: true, example: 'Desa Sukosari Lor'),
                    new OA\Property(property: 'rt', type: 'string', nullable: true, example: '001'),
                    new OA\Property(property: 'rw', type: 'string', nullable: true, example: '002'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Registrasi berhasil',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'Validasi gagal (NIK sudah terdaftar, password tidak cocok, dll)',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'nik'           => ['required', 'string', 'digits:16', 'unique:users'],
            'name'          => ['required', 'string', 'max:255'],
            'no_whatsapp'   => ['required', 'string', 'max:20'],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['nullable', 'string', 'in:L,P'],
            'pekerjaan'     => ['nullable', 'string', 'max:255'],
            'alamat'        => ['nullable', 'string', 'max:500'],
            'desa'          => ['nullable', 'string', 'exists:desas,nama'],
            'rt'            => ['nullable', 'string', 'max:10'],
            'rw'            => ['nullable', 'string', 'max:10'],
        ]);

        $user = User::create([
            'nik'           => $request->nik,
            'name'          => $request->name,
            'no_whatsapp'   => $request->no_whatsapp,
            'password'      => Hash::make($request->password),
            'role'          => 'warga',
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan'     => $request->pekerjaan,
            'alamat'        => $request->alamat,
            'desa'          => $request->desa,
            'rt'            => $request->rt,
            'rw'            => $request->rw,
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'token'   => $token,
            'user'    => [
                'id'            => $user->id,
                'nik'           => $user->nik,
                'name'          => $user->name,
                'no_whatsapp'   => $user->no_whatsapp,
                'tanggal_lahir' => $user->tanggal_lahir?->format('Y-m-d'),
                'jenis_kelamin' => $user->jenis_kelamin,
                'pekerjaan'     => $user->pekerjaan,
                'alamat'        => $user->alamat,
                'desa'          => $user->desa,
                'rt'            => $user->rt,
                'rw'            => $user->rw,
                'role'          => $user->role,
            ],
        ], 201);
    }

    #[OA\Post(
        path: '/auth/login',
        summary: 'Login warga',
        description: 'Login menggunakan NIK dan password. Hanya akun dengan role "warga" yang dapat login via API mobile. Token lama akan dihapus dan diganti token baru.',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nik', 'password'],
                properties: [
                    new OA\Property(property: 'nik', type: 'string', minLength: 16, maxLength: 16, example: '3277010101900001'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login berhasil',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthResponse')
            ),
            new OA\Response(
                response: 403,
                description: 'Akun bukan warga (admin tidak bisa login via mobile)',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(
                response: 422,
                description: 'NIK atau password salah',
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
            ),
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'nik'      => ['required', 'string', 'digits:16'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('nik', $request->nik)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nik' => ['NIK atau password salah.'],
            ]);
        }

        if ($user->role !== 'warga') {
            return response()->json([
                'message' => 'Akun ini tidak dapat login melalui aplikasi mobile.',
            ], 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token'   => $token,
            'user'    => [
                'id'          => $user->id,
                'nik'         => $user->nik,
                'name'        => $user->name,
                'no_whatsapp' => $user->no_whatsapp,
                'role'        => $user->role,
            ],
        ]);
    }

    #[OA\Post(
        path: '/auth/logout',
        summary: 'Logout warga',
        description: 'Menghapus token aktif. Setelah logout, token tidak bisa digunakan lagi.',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout berhasil',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
            new OA\Response(response: 401, description: 'Unauthenticated — token tidak valid atau tidak ada'),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }
}
