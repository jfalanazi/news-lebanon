<?php
namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // المدير (أنت)
        User::updateOrCreate(
            ['email' => 'info@jaber.sa'],
            [
                'name'     => 'جابر',
                'password' => Hash::make('Change_Me_123'), // غيّرها بعد أول دخول
                'role'     => UserRole::Admin,
            ]
        );

        // محرّر تجريبي (احذفه أو غيّره لاحقًا)
        User::updateOrCreate(
            ['email' => 'editor@nashra.local'],
            [
                'name'     => 'محرّر',
                'password' => Hash::make('Editor_123'),
                'role'     => UserRole::Editor,
            ]
        );

        // ناشر تجريبي
        User::updateOrCreate(
            ['email' => 'publisher@nashra.local'],
            [
                'name'     => 'ناشر',
                'password' => Hash::make('Publisher_123'),
                'role'     => UserRole::Publisher,
            ]
        );
    }
}
