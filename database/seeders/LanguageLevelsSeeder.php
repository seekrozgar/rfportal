<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LanguageLevel;
use Illuminate\Support\Str;

class LanguageLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $levels = $this->getLanguageLevelsList();
        $newCount = 0;
        $skippedCount = 0;

        foreach ($levels as $level) {
            $slug = Str::slug($level['name']);

            $existing = LanguageLevel::where('slug', $slug)->first();

            if ($existing) {
                $skippedCount++;
                continue;
            }

            LanguageLevel::create([
                'name' => $level['name'],
                'slug' => $slug,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $newCount++;
        }

        $this->command->info('✅ ' . $newCount . ' new language levels added!');
        $this->command->info('⏭️  ' . $skippedCount . ' duplicate levels skipped.');
    }

    private function getLanguageLevelsList(): array
    {
        return [
            ['name' => 'Native / Bilingual'],
            ['name' => 'Fluent'],
            ['name' => 'Professional Working'],
            ['name' => 'Full Professional'],
            ['name' => 'Limited Working'],
            ['name' => 'Beginner'],
        ];
    }
}
