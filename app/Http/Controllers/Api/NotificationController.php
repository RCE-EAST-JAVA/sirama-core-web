<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class NotificationController extends Controller
{
    #[OA\Get(
        path: '/notifications',
        summary: 'Daftar notifikasi user',
        description: 'Mengembalikan daftar notifikasi user yang sedang login dengan pagination',
        tags: ['Notifications'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Jumlah notifikasi per halaman',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 20)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'string', example: 'uuid'),
                                new OA\Property(property: 'type', type: 'string'),
                                new OA\Property(property: 'data', type: 'object', properties: [
                                    new OA\Property(property: 'title', type: 'string'),
                                    new OA\Property(property: 'message', type: 'string'),
                                    new OA\Property(property: 'pengajuan_id', type: 'integer', nullable: true),
                                ]),
                                new OA\Property(property: 'read_at', type: 'string', format: 'datetime', nullable: true),
                                new OA\Property(property: 'created_at', type: 'string', format: 'datetime'),
                            ]
                        )),
                        new OA\Property(property: 'meta', type: 'object', properties: [
                            new OA\Property(property: 'current_page', type: 'integer'),
                            new OA\Property(property: 'last_page', type: 'integer'),
                            new OA\Property(property: 'per_page', type: 'integer'),
                            new OA\Property(property: 'total', type: 'integer'),
                            new OA\Property(property: 'unread_count', type: 'integer'),
                        ]),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 20);

        $notifications = Auth::user()
            ->notifications()
            ->paginate($perPage);

        return response()->json([
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'per_page'     => $notifications->perPage(),
                'total'        => $notifications->total(),
                'unread_count' => Auth::user()->unreadNotifications()->count(),
            ],
        ]);
    }

    #[OA\Get(
        path: '/notifications/unread-count',
        summary: 'Jumlah notifikasi belum dibaca',
        description: 'Mengembalikan jumlah notifikasi yang belum dibaca',
        tags: ['Notifications'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'unread_count', type: 'integer', example: 5),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function unreadCount(): JsonResponse
    {
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'unread_count' => $count,
        ]);
    }

    #[OA\Post(
        path: '/notifications/{id}/read',
        summary: 'Tandai notifikasi sudah dibaca',
        description: 'Menandai satu notifikasi sebagai sudah dibaca',
        tags: ['Notifications'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID notifikasi',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Notification marked as read'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Notifikasi tidak ditemukan'),
        ]
    )]
    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    #[OA\Post(
        path: '/notifications/read-all',
        summary: 'Tandai semua notifikasi sudah dibaca',
        description: 'Menandai semua notifikasi user sebagai sudah dibaca',
        tags: ['Notifications'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'All notifications marked as read'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function markAllAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    #[OA\Delete(
        path: '/notifications/{id}',
        summary: 'Hapus notifikasi',
        description: 'Menghapus satu notifikasi',
        tags: ['Notifications'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID notifikasi',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Notification deleted'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Notifikasi tidak ditemukan'),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted',
        ]);
    }
}
