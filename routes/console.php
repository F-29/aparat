<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('aparat:clear', function () {
    clear_storage('videos');
    $this->info("clear uploaded video files...");

    clear_storage('category');
    $this->info("clear uploaded category files...");

    clear_storage('channel');
    $this->info("clear uploaded channel files...");
})->describe('Clears the storage from all temporary files');
