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