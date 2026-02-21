<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@rateflix.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'jane@example.com'],
            [
                'name' => 'Jane Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        $movies = [
            ['The Shawshank Redemption', 'Drama', '1994-09-23', 'https://image.tmdb.org/t/p/w500/9cqNxx0GxF0bflZmeSMuL5tnGzr.jpg'],
            ['Inception', 'Sci-Fi', '2010-07-16', 'https://image.tmdb.org/t/p/w500/xlaY2zyzMfkhk0HSC5VUwzoZPU1.jpg'],
            ['Interstellar', 'Sci-Fi', '2014-11-07', 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg'],
            ['The Dark Knight', 'Action', '2008-07-18', 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg'],
            ['The Matrix', 'Sci-Fi', '1999-03-31', 'https://image.tmdb.org/t/p/w500/p96dm7sCMn4VYAStA6siNz30G1r.jpg'],
            ['Fight Club', 'Drama', '1999-10-15', 'https://image.tmdb.org/t/p/w500/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg'],
            ['Pulp Fiction', 'Crime', '1994-10-14', 'https://image.tmdb.org/t/p/w500/vQWk5YBFWF4bZaofAbv0tShwBvQ.jpg'],
            ['The Godfather', 'Crime', '1972-03-24', 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg'],
            ['Gladiator', 'Action', '2000-05-05', 'https://image.tmdb.org/t/p/w500/ty8TGRuvJLPUmAR1H1nRIsgwvim.jpg'],
            ['Parasite', 'Drama', '2019-05-30', 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg'],
            ['Whiplash', 'Drama', '2014-10-10', 'https://image.tmdb.org/t/p/w500/7fn624j5lj3xTme2SgiLCeuedmO.jpg'],
            ['The Silence of the Lambs', 'Crime', '1991-02-14', 'https://image.tmdb.org/t/p/w500/uS9m8OBk1A8eM9I042bx8XXpqAq.jpg'],
        ];

        foreach ($movies as [$title, $genre, $release, $poster]) {
            Movie::updateOrCreate(
                ['title' => $title],
                [
                    'description' => $title . ' is one of the featured films on RateFlix.',
                    'genre' => $genre,
                    'release_date' => $release,
                    'poster_url' => $poster,
                    'created_by' => $admin->id,
                ]
            );
        }

        $shawshank = Movie::where('title', 'The Shawshank Redemption')->first();
        if ($shawshank) {
            Review::updateOrCreate(
                ['movie_id' => $shawshank->id, 'user_id' => $user->id],
                [
                    'rating' => 10,
                    'review_text' => 'An absolute masterpiece. Timeless and deeply moving.',
                    'status' => 'approved',
                ]
            );
        }
    }
}
