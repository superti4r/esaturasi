<?php

namespace Database\Seeders;

use App\Models\Archive;
use Illuminate\Database\Seeder;

class ArchiveSeeder extends Seeder
{
    public function run(): void
    {
        // Semua data bergantung pada archive aktif.
        // Pastikan ada arsip default dan aktif.
        Archive::query()->update(['status' => 'Not Active']);

        Archive::firstOrCreate(
            ['name' => 'default'],
            ['semester' => 'Ganjil', 'status' => 'Active']
        );

        // Kalau record sudah ada tapi statusnya bukan Active (mis. hasil migrate:fresh lama), aktifkan.
        Archive::where('name', 'default')->update(['status' => 'Active']);
    }
}
