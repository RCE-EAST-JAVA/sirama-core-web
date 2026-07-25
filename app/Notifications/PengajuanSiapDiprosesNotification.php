<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PengajuanSiapDiprosesNotification extends Notification
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
            'title' => 'Pengajuan Siap Diproses',
            'body'  => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} dari Desa {$this->pengajuan->desa} telah diverifikasi dan siap diproses.",
            'data'  => ['pengajuan_id' => (string) $this->pengajuan->id],
        ];
    }



    /**
     * Get the array representation of the notification for database.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'         => 'Pengajuan Siap Diproses',
            'message'       => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} dari Desa {$this->pengajuan->desa} telah diverifikasi dan siap diproses.",
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'pemohon_nama'  => $this->pengajuan->user->name,
            'pemohon_nik'   => $this->pengajuan->user->nik,
            'desa'          => $this->pengajuan->desa,
            'action'        => 'process',
            'icon'          => 'check-square',
            'color'         => 'info',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'         => 'Pengajuan Siap Diproses',
            'message'       => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} dari Desa {$this->pengajuan->desa} telah diverifikasi dan siap diproses.",
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'pemohon_nama'  => $this->pengajuan->user->name,
            'desa'          => $this->pengajuan->desa,
            'action'        => 'process',
        ]);
    }
}
