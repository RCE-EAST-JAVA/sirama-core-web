<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PatchSwaggerServers extends Command
{
    protected $signature   = 'swagger:patch-servers';
    protected $description = 'Inject servers array ke api-docs.json setelah l5-swagger:generate';

    public function handle(): int
    {
        $path = storage_path('api-docs/api-docs.json');

        if (! file_exists($path)) {
            $this->error('api-docs.json tidak ditemukan. Jalankan l5-swagger:generate terlebih dahulu.');
            return self::FAILURE;
        }

        $json = json_decode(file_get_contents($path), true);

        $json['servers'] = [
            [
                'url'         => config('app.url') . '/api',
                'description' => 'API Server',
            ],
        ];

        file_put_contents($path, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info('servers berhasil di-inject ke api-docs.json: ' . config('app.url') . '/api');

        return self::SUCCESS;
    }
}
