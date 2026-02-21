# RateFlix (Laravel)

RateFlix is a movie review web app built with **Laravel + MySQL**.

It includes:
- movie browsing with search + genre filters
- movie details with poster, rating summary, and reviews
- user authentication (register/login/logout)
- user dashboard to manage own reviews
- admin dashboard to manage movies, reviews, and users
- TMDB poster integration with sync command

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL (XAMPP)
- Blade templates
- Vanilla CSS/JS (ported from your original app)

## Database

Configure your own `.env` database values (example):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

## Quick Start

From the project root:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

Open:

`http://127.0.0.1:8000`

## Demo Credentials

- Admin: `admin@rateflix.com` / `password`
- User: `jane@example.com` / `password`

Use demo credentials only for local development. Change/remove them for production.

## Main Features

### Public
- Browse movies
- Search by title/genre
- Filter by genre
- View movie detail page

### Authenticated User
- Submit review (1-10)
- Edit own review
- Delete own review
- View personal dashboard (`/dashboard`)

### Admin
- Admin dashboard (`/admin`)
- Add/Edit/Delete movies
- Approve/Delete any review
- View user list
- Delete user reviews from movie page and admin dashboard

## TMDB Configuration

Set either API key or access token in `.env`:

```env
TMDB_API_KEY=
TMDB_ACCESS_TOKEN=
TMDB_IMAGE_SIZE=w500
```

If poster URL is left blank while creating/updating a movie, RateFlix tries TMDB lookup automatically.
Never commit real API keys/tokens to git.

### Sync Posters for Existing Movies

```bash
php artisan posters:sync --overwrite
```

## Useful Commands

```bash
php artisan route:list
php artisan optimize:clear
php artisan db:seed
```

## Notes

- This Laravel app is separate from the older plain PHP app (`movie-review-app`).
- It uses a separate database: `rateflix_laravel_db`.
