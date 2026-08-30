# Axis Industrial Laravel backend

Laravel API and bilingual administration panel for the CNC and lathe supplies website.

## Requirements

- PHP 8.3+
- Composer
- MySQL
- Node.js and npm for compiling admin assets

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Production deployment

The admin templates use Laravel Vite. Every deployment must contain all of `public/build`, including:

```text
public/build/manifest.json
public/build/assets/*.css
public/build/assets/*.js
```

Build before uploading:

```bash
npm ci
npm run build
```

If Node.js is unavailable on the production server, run those commands locally and upload the complete `public/build` directory with the Laravel application. The directory is intentionally not ignored so compiled assets can be included in shared-hosting deployments.

After uploading, run these commands from the Laravel project root:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Ensure the domain document root points to Laravel's `public` directory and that `.env` contains the production `APP_URL`, database credentials, and mail configuration.

## API

The public API is versioned under `/api/v1`. See `openapi.yaml` for documented endpoints.

## Verification

```bash
vendor/bin/pint --test
php artisan test
npm run build
```
