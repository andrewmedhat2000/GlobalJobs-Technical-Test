<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredFiles extends Command
{
    protected $signature = 'files:delete-expired';
    protected $description = 'Delete expired files';

    public function handle()
    {
        $files = Storage::files('uploads');
        foreach ($files as $file) {
            if (now()->diffInHours(Storage::lastModified($file)) > 24) {
                Storage::delete($file);
            }
        }
    }
}
