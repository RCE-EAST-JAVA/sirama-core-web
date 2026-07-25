<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class FcmTokenController extends Controller
{
    #[OA\Post(
        path: '/fcm-token',
        summary: 'Simpan FCM token untuk push notification',
        description: 'Digunakan oleh aplikasi mobile untuk mendaftarkan device token Firebase Cloud Messaging.',
        tags: ['FCM'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['fcm_token'],
                properties: [
                    new OA\Property(property: 'fcm_token', type: 'string', description: 'Firebase Cloud Messaging device token'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'FCM token berhasil disimpan'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(Request $request): JsonResponse
    {
        $request->validate(['fcm_token' => 'required|string']);

        $user = Auth::user();
        $user->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => 'FCM token berhasil disimpan.']);
    }
}
