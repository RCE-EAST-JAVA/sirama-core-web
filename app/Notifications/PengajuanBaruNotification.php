<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PengajuanBaruNotification extends Notification
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
        return ['database', 'broadcast', FcmChannel::class];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Baru',
            'body'  => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} dari {$this->pengajuan->user->name} perlu diverifikasi.",
            'data'  => ['pengajuan_id' => (string) $this->pengajuan->id],
        ];
    }



    /**
     * Get the array representation of the notification for database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'         => 'Pengajuan Baru',
            'message'       => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} dari {$this->pengajuan->user->name} perlu diverifikasi.",
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'pemohon_nama'  => $this->pengajuan->user->name,
            'pemohon_nik'   => $this->pengajuan->user->nik,
            'desa'          => $this->pengajuan->desa,
            'action'        => 'verify',
            'icon'          => 'file-text',
            'color'         => 'warning',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'         => 'Pengajuan Baru',
            'message'       => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} dari {$this->pengajuan->user->name} perlu diverifikasi.",
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'pemohon_nama'  => $this->pengajuan->user->name,
            'action'        => 'verify',
        ]);
    }
}
