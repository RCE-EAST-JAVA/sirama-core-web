<?php

namespace App\Events;

use App\Models\Pengajuan;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusPengajuanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Pengajuan $pengajuan)
    {
        //
    }

    /**
     * Broadcast ke private channel milik user (warga) yang bersangkutan.
     * Frontend listen: Echo.private(`pengajuan.${userId}`)
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('pengajuan.'.$this->pengajuan->user_id),
        ];
    }

    /**
     * Data yang dikirim ke frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'pengajuan_id'  => $this->pengajuan->id,
            'jenis_layanan' => $this->pengajuan->getLabelJenisLayanan(),
            'status'        => $this->pengajuan->status,
            'status_label'  => $this->pengajuan->getLabelStatus(),
            'updated_at'    => $this->pengajuan->updated_at->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'status.updated';
    }
}
