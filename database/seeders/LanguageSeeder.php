<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇬🇧',
                'flag_class' => 'flag-icon-gb',
                'is_active' => true,
                'is_default' => true,
                'order' => 1,
                'direction' => 'ltr',
            ],
            [
                'code' => 'ur',
                'name' => 'Urdu',
                'native_name' => 'اردو',
                'flag' => '🇵🇰',
                'flag_class' => 'flag-icon-pk',
                'is_active' => true,
                'is_default' => false,
                'order' => 2,
                'direction' => 'rtl',
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '🇸🇦',
                'flag_class' => 'flag-icon-sa',
                'is_active' => true,
                'is_default' => false,
                'order' => 3,
                'direction' => 'rtl',
            ],
        ];

        foreach ($languages as $lang) {
            // ✅ Use firstOrCreate to avoid duplicates
            Language::firstOrCreate(
                ['code' => $lang['code']],
                $lang
            );
        }

        $this->command->info('✅ Languages seeded successfully!');
    }
}
