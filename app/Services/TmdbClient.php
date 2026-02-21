<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbClient
{
    public function enabled(): bool
    {
        return filled(config('services.tmdb.token')) || filled(config('services.tmdb.key'));
    }

    public function searchPosterUrl(string $title, ?string $releaseDate = null): ?string
    {
        if (!$this->enabled() || trim($title) === '') {
            return null;
        }

        $params = [
            'query' => $title,
            'include_adult' => false,
            'language' => 'en-US',
            'page' => 1,
        ];

        if (!empty($releaseDate)) {
            $params['year'] = date('Y', strtotime($releaseDate));
        }

        $request = Http::timeout(8)->acceptJson();

        if (filled(config('services.tmdb.token'))) {
            $request = $request->withToken(config('services.tmdb.token'));
        } else {
            $params['api_key'] = config('services.tmdb.key');
        }

        $response = $request->get('https://api.themoviedb.org/3/search/movie', $params);

        if (!$response->successful()) {
            return null;
        }

        $results = $response->json('results', []);
        foreach ($results as $movie) {
            if (!empty($movie['poster_path'])) {
                return sprintf(
                    'https://image.tmdb.org/t/p/%s/%s',
                    config('services.tmdb.image_size', 'w500'),
                    ltrim($movie['poster_path'], '/')
                );
            }
        }

        return null;
    }
}
