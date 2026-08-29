<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jasa;
use App\Models\Tim;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin Ruangisasi',
            'email' => 'superadmin@ruangisasi.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Admin Ruangisasi',
            'email' => 'admin@ruangisasi.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Jasa::insert([
            ['nama' => 'Dekorasi Acara', 'deskripsi' => 'Jasa dekorasi untuk acara dan kegiatan.', 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dokumentasi Foto & Video', 'deskripsi' => 'Dokumentasi foto dan video kegiatan.', 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sound System', 'deskripsi' => 'Penyediaan dan pengelolaan sound system.', 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Tim::insert([
            ['nama_tim' => 'Tim Dekorasi A', 'deskripsi' => 'Tim khusus dekorasi acara.', 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tim' => 'Tim Dokumentasi A', 'deskripsi' => 'Tim foto dan video.', 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
            ['nama_tim' => 'Tim Audio A', 'deskripsi' => 'Tim sound system dan audio.', 'status' => 'aktif', 'created_at' => now(), 'updated_at' => now()],
        ]);

        User::create([
            'name' => 'User Ruangisasi',
            'email' => 'user@ruangisasi.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
