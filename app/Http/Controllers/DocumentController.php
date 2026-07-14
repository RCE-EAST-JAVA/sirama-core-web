<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    /**
     * Resolve file path dari form detail, termasuk handle array (kk_perbaikan)
     */
    private function resolveFilePath(Pengajuan $pengajuan, string $field): string
    {
        $user = auth()->user();

        // Permission check berdasarkan role
        if ($user->role === 'warga') {
            if ($pengajuan->user_id !== $user->id) abort(403);
        } elseif ($user->role === 'admin_desa') {
            if ($pengajuan->user->desa !== $user->desa) abort(403);
        }
        // admin_kecamatan dan admin_aplikasi bisa akses semua

        $formDetail = $pengajuan->getFormDetail();
        if (!$formDetail) abort(404, 'Form detail tidak ditemukan');
        if (!array_key_exists($field, $formDetail->getAttributes())) abort(404, 'Field tidak ditemukan');

        $filePath = $formDetail->$field;
        if (!$filePath) abort(404, 'File tidak ditemukan');

        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
            if (!$filePath) abort(404, 'File tidak ditemukan');
        }

        if (!Storage::disk('local')->exists($filePath)) abort(404, 'File tidak ditemukan di storage');

        return $filePath;
    }

    /**
     * Return file sebagai base64 JSON – IDM tidak bisa intercept data URL
     */
    public function data(Request $request, Pengajuan $pengajuan, string $field): JsonResponse
    {
        $user = auth()->user();

        // Permission check berdasarkan role
        if ($user->role === 'warga') {
            if ($pengajuan->user_id !== $user->id) abort(403);
        } elseif ($user->role === 'admin_desa') {
            if ($pengajuan->user->desa !== $user->desa) abort(403);
        }

        $formDetail = $pengajuan->getFormDetail();
        if (!$formDetail) {
            return response()->json(['error' => 'Form detail tidak ditemukan'], 404);
        }

        if (!array_key_exists($field, $formDetail->getAttributes())) {
            return response()->json(['error' => 'Field tidak ditemukan'], 404);
        }

        $filePath = $formDetail->$field;
        if (!$filePath) {
            return response()->json(['error' => 'File belum diupload'], 404);
        }

        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
            if (!$filePath) {
                return response()->json(['error' => 'File belum diupload'], 404);
            }
        }

        if (!Storage::disk('local')->exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan di server. Mungkin perlu diupload ulang.'], 404);
        }

        $content  = Storage::disk('local')->get($filePath);
        $mimeType = Storage::disk('local')->mimeType($filePath);
        $base64   = base64_encode($content);

        return response()->json([
            'data'     => 'data:' . $mimeType . ';base64,' . $base64,
            'mime'     => $mimeType,
            'filename' => basename($filePath),
        ]);
    }

    /**
     * Serve softfile dari lokasi_dokumen (array JSON) berdasarkan index
     */
    public function softfile(Request $request, Pengajuan $pengajuan, int $index): Response
    {
        $user = auth()->user();

        // Permission check
        if ($user->role === 'warga') {
            if ($pengajuan->user_id !== $user->id) abort(403);
        } elseif ($user->role === 'admin_desa') {
            if ($pengajuan->user->desa !== $user->desa) abort(403);
        }

        $files = $pengajuan->lokasi_dokumen ?? [];

        if (!isset($files[$index])) abort(404, 'Softfile tidak ditemukan');

        $filePath = $files[$index];

        if (!Storage::disk('local')->exists($filePath)) abort(404, 'File tidak ditemukan di storage');

        $content  = Storage::disk('local')->get($filePath);
        $mimeType = Storage::disk('local')->mimeType($filePath);
        $fileName = basename($filePath);

        return response($content, 200, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control'       => 'private, no-store',
        ]);
    }

    /**
     * Serve file langsung (fallback)
     */
    public function show(Request $request, Pengajuan $pengajuan, string $field): Response
    {
        $filePath = $this->resolveFilePath($pengajuan, $field);

        $content  = Storage::disk('local')->get($filePath);
        $mimeType = Storage::disk('local')->mimeType($filePath);
        $fileName = basename($filePath);

        return response($content, 200, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control'       => 'private, no-store',
        ]);
    }
}
