<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run()
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'native_name' => 'English', 'flag' => '🇬🇧', 'is_default' => true, 'order' => 1],
            ['code' => 'ur', 'name' => 'Urdu', 'native_name' => 'اردو', 'flag' => '🇵🇰', 'is_default' => false, 'order' => 2],
            ['code' => 'ar', 'name' => 'Arabic', 'native_name' => 'العربية', 'flag' => '🇸🇦', 'is_default' => false, 'order' => 3],
            ['code' => 'fr', 'name' => 'French', 'native_name' => 'Français', 'flag' => '🇫🇷', 'is_default' => false, 'order' => 4],
            ['code' => 'es', 'name' => 'Spanish', 'native_name' => 'Español', 'flag' => '🇪🇸', 'is_default' => false, 'order' => 5],
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
