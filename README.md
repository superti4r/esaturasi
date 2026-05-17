<p align="center"><img src="https://raw.githubusercontent.com/superti4r/esaturasi/refs/heads/main/public/esaturasi-header.svg" alt="project-image"></p>

<p align="center"><img src="https://img.shields.io/badge/PHP-8.4-brightgreen" alt="shields"><img src="https://img.shields.io/badge/Laravel-12-red?logo=laravel&amp;logoColor=white" alt="shields"><img src="https://img.shields.io/badge/Filament-3.3-6366f1?logo=laravel&amp;logoColor=white" alt="shields"><img src="https://img.shields.io/badge/Tailwind-4-06b6d4?logo=tailwindcss&amp;logoColor=white" alt="shields"></p>

  
  
<h2>🧐 Fitur</h2>

*   Manajemen User (Guru & Siswa)
*   Kelola Jurusan & kelas
*   Jadwal
*   Pengelolaan Role
*   Manajemen Materi & Tugas
*   Cetak PDF
*   Penilaian Tugas Siswa

<h2>🛠️ Langkah Installasi</h2>

## Menjalankan dengan Docker (disarankan)

Repo ini sudah dilengkapi setup Docker terpisah untuk **staging** dan **production** termasuk Nginx, PHP-FPM (Laravel), MySQL, Redis, Prometheus, dan Grafana.

### Prasyarat

* Docker Engine + Docker Compose v2

### Staging (lokal)

1. Salin file env lokal untuk docker.

> Catatan: untuk lokal/staging kamu boleh menyimpan env di file lokal. Untuk CI/CD dan server production, **jangan commit `.env`** dan gunakan GitHub Secrets (lihat bagian CI/CD).

```bash
cp .env.example .env
```

2. Isi minimal variabel berikut di `.env`:

* `APP_KEY` (gunakan `php artisan key:generate --show` sekali)
* `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_ROOT_PASSWORD`
* `APP_URL` (default: `http://localhost:8080`)
* `GRAFANA_ADMIN_PASSWORD`

3. Jalankan stack staging:

```bash
docker compose -f compose.staging.yml up -d --build
```

4. Akses aplikasi:

* App: `http://localhost:8080`
* Prometheus: `http://localhost:9090`
* Grafana: `http://localhost:3000` (user: `admin`, password: dari `GRAFANA_ADMIN_PASSWORD`)

### Production

Untuk production, image aplikasi dibuild dan dipush ke GHCR lewat GitHub Actions, lalu server menarik image tersebut.

File `compose.production.yml` mengharapkan variabel berikut tersedia di server (biasanya diletakkan di `~/apps/esaturasi/.env`):

* `APP_IMAGE` (contoh: `ghcr.io/<owner>/<repo>:<sha>`)
* `APP_URL`, `APP_KEY`
* `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_ROOT_PASSWORD`
* `GRAFANA_ADMIN_PASSWORD`

Jalankan di server:

```bash
docker compose -f compose.production.yml up -d
```

## CI/CD (GitHub Actions)

Workflow:

* `CI`: `.github/workflows/ci.yml` menjalankan test Laravel.
* `CD`: `.github/workflows/cd.yml` build & push image ke **GHCR**, lalu deploy ke **staging** (branch `staging`) menggunakan **GitHub Environments** + **Secrets** (PHP-FPM + Nginx via docker compose di VPS).

### Secrets yang dibutuhkan

Buat **GitHub Environment**: `staging`, lalu isi secrets berikut:

* `SSH_HOST`, `SSH_USER`, `SSH_KEY`, `SSH_PORT`
* `APP_URL`, `APP_KEY`
* `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

> `.env` untuk container dibuat di VPS saat deploy (dari secrets), tidak disimpan di repository.

<p>1. Copy .env</p>

```
cp .env.example .env
```

<p>2. Install</p>

```
composer install
```

<p>3. Generate key untuk Laravel</p>

```
php artisan key:generate
```

<p>4. Link Penyimpanan</p>

```
php artisan storage:link
```

<p>5. Migrasi Database</p>

```
php artisan migrate
```

<p>6. Generate semua izin</p>

```
php artisan shield:generate --all
```

<p>7. Buat user pertama kali</p>

```
php artisan make:filament-user
```

<p>8. Buat user pertama yang sudah dibuat menjadi Administrator</p>

```
php artisan shield:super-admin --user=1 --panel=m
```

<p>9. Jalankan</p>

```
php artisan serve
```

  
  
<h2>💻 Dibangun Menggunakan</h2>

*   PHP 8.4
*   Laravel 12
*   TailwindCSS v4
*   Filament v3
*   Spatie Permission