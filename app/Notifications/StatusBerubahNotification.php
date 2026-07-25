<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class StatusBerubahNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Pengajuan $pengajuan, public readonly ?string $catatan = null)
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
            'title' => 'Status Pengajuan Berubah',
            'body'  => $this->getMessage(),
            'data'  => ['pengajuan_id' => (string) $this->pengajuan->id],
        ];
    }



    /**
     * Get the array representation of the notification for database.
     */
    public function toArray(object $notifiable): array
    {
        $statusLabel = $this->pengajuan->getLabelStatus();
        
        return [
            'title'         => 'Status Pengajuan Berubah',
            'message'       => $this->getMessage(),
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'status_label'  => $statusLabel,
            'catatan'       => $this->catatan,
            'action'        => $this->getAction(),
            'icon'          => $this->getIcon(),
            'color'         => $this->getColor(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title'         => 'Status Pengajuan Berubah',
            'message'       => $this->getMessage(),
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->jenis_layanan,
            'status'        => $this->pengajuan->status,
            'status_label'  => $this->pengajuan->getLabelStatus(),
            'catatan'       => $this->catatan,
            'action'        => $this->getAction(),
        ]);
    }

    private function getMessage(): string
    {
        return match ($this->pengajuan->status) {
            'diverifikasi_desa' => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda telah diverifikasi oleh Admin Desa dan diteruskan ke Kecamatan.",
            'ditolak_desa' => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda ditolak oleh Admin Desa. " . ($this->catatan ? "Alasan: {$this->catatan}" : "Silakan ajukan kembali dengan perbaikan."),
            'diverifikasi_kecamatan' => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda sedang diproses oleh Kecamatan.",
            'ditolak_kecamatan' => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda ditolak oleh Admin Kecamatan. " . ($this->catatan ? "Alasan: {$this->catatan}" : "Silakan ajukan kembali dengan perbaikan."),
            'selesai' => "Pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda telah selesai diproses. Dokumen siap diambil.",
            default => "Status pengajuan {$this->pengajuan->getLabelJenisLayanan()} Anda: {$this->pengajuan->getLabelStatus()}",
        };
    }

    private function getAction(): string
    {
        return match ($this->pengajuan->status) {
            'ditolak_desa', 'ditolak_kecamatan' => 'resubmit',
            'selesai' => 'download',
            default => 'view_detail',
        };
    }

    private function getIcon(): string
    {
        return match ($this->pengajuan->status) {
            'diverifikasi_desa', 'diverifikasi_kecamatan' => 'clock',
            'ditolak_desa', 'ditolak_kecamatan' => 'x-circle',
            'selesai' => 'check-circle',
            default => 'info',
        };
    }

    private function getColor(): string
    {
        return match ($this->pengajuan->status) {
            'diverifikasi_desa', 'diverifikasi_kecamatan' => 'info',
            'ditolak_desa', 'ditolak_kecamatan' => 'danger',
            'selesai' => 'success',
            default => 'secondary',
        };
    }
}
