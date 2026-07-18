<?php
namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            ['name' => 'النهار', 'domain' => 'annahar.com'],
            ['name' => 'الأخبار', 'domain' => 'al-akhbar.com'],
            ['name' => 'LBCI', 'domain' => 'lbcgroup.tv'],
            ['name' => 'MTV', 'domain' => 'mtv.com.lb'],
            ['name' => 'الجديد', 'domain' => 'aljadeed.tv'],
            ['name' => 'الوكالة الوطنية', 'domain' => 'nna-leb.gov.lb'],
            ['name' => 'الشرق الأوسط', 'domain' => 'aawsat.com'],
            ['name' => 'رويترز', 'domain' => 'reuters.com'],
        ];
        foreach ($sources as $s) {
            Source::updateOrCreate(['name' => $s['name']], $s + ['is_active' => true]);
        }
    }
}
