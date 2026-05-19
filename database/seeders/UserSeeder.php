<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@esaturasi.local'],
            [
                'name' => 'Bachtiar Dwi Pramudi',
                'email' => 'bchtrrprmd@gmail.com',
                'password' => Hash::make('admin123.'),
            ]
        );
    }
}
