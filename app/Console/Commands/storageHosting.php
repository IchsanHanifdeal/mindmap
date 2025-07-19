<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageHosting extends Command
{
    protected $signature = 'app:storage-hosting';
    protected $description = 'Generate perintah symbolic link storage to public_html untuk dipasang di terminal/cronjob';

    public function handle()
    {
        $username = $this->ask('Masukkan username hosting (misal: mind5961)');
        $projectdir = $this->ask('Masukkan nama folder project Laravel (misal: mindmapping)');

        $storagePath = "/home/{$username}/{$projectdir}/storage/app/public";
        $publicHtmlPath = "/home/{$username}/public_html/storage";

        $this->line('----------------------------------------');
        $this->info('✅ Berikut perintah ln -s yang bisa kamu jalankan di terminal:');
        $this->line("ln -s {$storagePath} {$publicHtmlPath}");
        $this->line('----------------------------------------');
    }
}
