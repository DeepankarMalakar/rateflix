<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'genre',
        'release_date',
        'poster_url',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public static function syncPostersFromTmdb(\App\Services\TmdbClient $tmdb, bool $overwrite = false): int
    {
        if (!$tmdb->enabled()) {
            return 0;
        }

        $updated = 0;

        static::query()->select(['id', 'title', 'release_date', 'poster_url'])->chunk(100, function ($movies) use ($tmdb, $overwrite, &$updated) {
            foreach ($movies as $movie) {
                if (!$overwrite && !blank($movie->poster_url)) {
                    continue;
                }

                $poster = $tmdb->searchPosterUrl($movie->title, $movie->release_date);
                if (blank($poster)) {
                    continue;
                }

                $movie->poster_url = $poster;
                $movie->save();
                $updated++;
            }
        });

        return $updated;
    }
}
