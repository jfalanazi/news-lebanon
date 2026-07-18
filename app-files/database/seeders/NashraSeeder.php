<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

// شغّلها بـ: php artisan db:seed --class=NashraSeeder
class NashraSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SourceSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
