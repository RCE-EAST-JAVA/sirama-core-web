<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PengajuanDiterimaNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification for database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'         => 'Pengajuan Diterima',
            'message'       => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda telah diterima dan sedang diproses.",
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'action'        => 'view_detail',
            'icon'          => 'check-circle',
            'color'         => 'success',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'         => 'Pengajuan Diterima',
            'message'       => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda telah diterima dan sedang diproses.",
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'action'        => 'view_detail',
        ]);
    }
}
