# Url Shortner made by me
I created this url shortner for fun using the CRUD principle and the Laravel framework

## Requirements
* PHP 8.3 or newer with the `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo` and `zip` extensions
* [Composer](https://getcomposer.org/) 2
* [Node.js](https://nodejs.org/) 18 or newer and npm (only needed to build the CSS/JS)
* MySQL or MariaDB

The app runs on Laravel 13. The CSS/JS are built with Vite, Bootstrap 5, Bootstrap Icons and jQuery.

## How to setup this project
1. Clone this repo
2. Install the PHP dependencies
   ```bash
   composer install
   ```
3. Create your `.env` and generate the app key
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Create an empty database (e.g. `urlshortner`) and set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` in `.env`
5. Run the migrations and seed the default forbidden keywords
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
6. Build the CSS/JS (the output in `public/build` is not committed)
   ```bash
   npm install
   npm run build
   ```
7. Point your web server's document root at the `public` folder (Laragon and Herd do this for you), or run the built-in server:
   ```bash
   php artisan serve
   ```

While working on the front end, run `npm run dev` instead of `npm run build` to get hot reloading.

## How to create an account?
* You must run this command: `php artisan create-admin {username} {password} {email}`
* Then log in at `/login` (it redirects to `/auth/login`) to reach the admin dashboard

## Useful commands
* `php artisan checkUrl {url}`: check if a url contains a forbidden keyword
* `php artisan test`: run the test suite

## Deploying
```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Upgrading an existing `.env` from the Laravel 5.7 version
Some keys were renamed. Update them or Laravel will fall back to its database-backed defaults:

| Old key            | New key                |
|--------------------|------------------------|
| `CACHE_DRIVER`     | `CACHE_STORE`          |
| `MAIL_DRIVER`      | `MAIL_MAILER`          |
| `BROADCAST_DRIVER` | `BROADCAST_CONNECTION` |

`SESSION_DRIVER=file`, `CACHE_STORE=file` and `QUEUE_CONNECTION=sync` match how the app ran before. Running `php artisan migrate` also creates the `cache` and `jobs` tables in case you prefer the database drivers.
