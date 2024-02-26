<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            ['name' => 'John Doe', 'email' => 'john@gmail.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@gmail.com'],
            ['name' => 'Alice Johnson', 'email' => 'alice@gmail.com'],
            ['name' => 'Bob Williams', 'email' => 'bob@gmail.com'],
            ['name' => 'Emily Brown', 'email' => 'emily@gmail.com'],
            ['name' => 'Michael Davis', 'email' => 'michael@gmail.com'],
            ['name' => 'Olivia Wilson', 'email' => 'olivia@gmail.com'],
            ['name' => 'William Taylor', 'email' => 'william@gmail.com'],
            ['name' => 'Sophia Martinez', 'email' => 'sophia@gmail.com'],
            ['name' => 'James Anderson', 'email' => 'james@gmail.com'],
            ['name' => 'Isabella Thomas', 'email' => 'isabella@gmail.com'],
            ['name' => 'Benjamin Hernandez', 'email' => 'benjamin@gmail.com'],
            ['name' => 'Mia Nelson', 'email' => 'mia@gmail.com'],
            ['name' => 'Alexander White', 'email' => 'alexander@gmail.com'],
            ['name' => 'Charlotte Young', 'email' => 'charlotte@gmail.com'],
            ['name' => 'Daniel Moore', 'email' => 'daniel@gmail.com'],
            ['name' => 'Sophia Lee', 'email' => 'sophialee@gmail.com'],
            ['name' => 'William Clark', 'email' => 'williamclark@gmail.com'],
            ['name' => 'Ava Hall', 'email' => 'avahall@gmail.com'],
            ['name' => 'Oliver King', 'email' => 'oliverking@gmail.com'],
            ['name' => 'Ethan Johnson', 'email' => 'ethan@gmail.com'],
            ['name' => 'Ella Thompson', 'email' => 'ella@gmail.com'],
            ['name' => 'Matthew Garcia', 'email' => 'matthew@gmail.com'],
            ['name' => 'Sophia Lopez', 'email' => 'sophialopez@gmail.com'],
            ['name' => 'William Martinez', 'email' => 'williammartinez@gmail.com'],
            ['name' => 'Amelia Perez', 'email' => 'amelia@gmail.com'],
            ['name' => 'Lucas Scott', 'email' => 'lucas@gmail.com'],
            ['name' => 'Avery Mitchell', 'email' => 'avery@gmail.com'],
            ['name' => 'Evelyn Carter', 'email' => 'evelyn@gmail.com'],
            ['name' => 'Jackson Rodriguez', 'email' => 'jackson@gmail.com'],
            ['name' => 'Sofia Harris', 'email' => 'sofia@gmail.com'],
            ['name' => 'Henry King', 'email' => 'henry@gmail.com'],
            ['name' => 'Liam Wright', 'email' => 'liam@gmail.com'],
            ['name' => 'Amelia Green', 'email' => 'amelia.green@gmail.com'],
            ['name' => 'Jashon Robert', 'email' => 'Jashon.Robert@gmail.com'],
        ];

        // Looping untuk menambahkan pengguna
        // foreach ($usersData as $userData) {
        //     DB::table('users')->insert([
        //         'name' => $userData['name'],
        //         'email' => $userData['email'],
        //         'role' => 'karyawan',
        //         'status' => 'aktif',
        //         'email_verified_at' => now(),
        //         'password' => Hash::make('password'),
        //         'remember_token' => Str::random(10),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
        $usersData = array_map(function ($userData) {
            return [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => 'karyawan',
                'status' => 'aktif',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $usersData);

        // Batch insert ke dalam database
        DB::table('users')->insert($usersData);
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 'superadmin',
                'status' => 'aktif',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dancok',
                'email' => 'Dancok@gmail.com',
                'role' => 'superadmin',
                'status' => 'nonaktif',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@gmail.com',
                'role' => 'supervisor',
                'status' => 'aktif',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HRD',
                'email' => 'hrd@gmail.com',
                'role' => 'hrd',
                'status' => 'aktif',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan pengguna lainnya sesuai kebutuhan
        ]);
    }
}
