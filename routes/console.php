<?php

use Illuminate\Foundation\Inspiring;
use App\Models\Movie;
use App\Services\TmdbClient;
use Illuminate\Support\Facades\Artisan;

// Example default Laravel command.
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync movie poster URLs from TMDB.
// Use: php artisan posters:sync --overwrite
Artisan::command('posters:sync {--overwrite}', function (TmdbClient $tmdb) {
    $count = Movie::syncPostersFromTmdb($tmdb, (bool) $this->option('overwrite'));
    $this->info("TMDB poster sync complete. Updated {$count} movie(s).");
})->purpose('Sync movie posters from TMDB');
