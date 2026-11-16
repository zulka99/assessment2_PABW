<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            ['Petugas', 'petugas@smartdrain.test', 'petugas'],
            ['Warga Satu', 'warga1@smartdrain.test', 'warga'],
            ['Warga Dua', 'warga2@smartdrain.test', 'warga'],
        ];

        foreach ($accounts as [$name, $email, $role]) {
            $user = User::firstOrNew(['email' => $email]);

            $user->name = $name;
            $user->password = Hash::make('password');
            $user->role = $role;

            $user->save();
        }
    }
}
