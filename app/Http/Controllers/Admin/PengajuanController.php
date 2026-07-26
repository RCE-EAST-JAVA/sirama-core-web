<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function show(Pengajuan $pengajuan): View
    {
        $pengajuan->loadMissing(['user', 'riwayatStatuses']);

        $formDetail = $pengajuan->getFormDetail();

        return view('admin.pengajuan.show', compact('pengajuan', 'formDetail'));
    }
}
