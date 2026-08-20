<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CareerLevel;
use Illuminate\Support\Str;

class CareerLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $levels = $this->getCareerLevelsList();
        $newCount = 0;
        $skippedCount = 0;

        foreach ($levels as $level) {
            $slug = Str::slug($level['name']);

            $existing = CareerLevel::where('slug', $slug)->first();

            if ($existing) {
                $skippedCount++;
                continue;
            }

            CareerLevel::create([
                'name' => $level['name'],
                'slug' => $slug,
                'level_order' => $level['level_order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $newCount++;
        }

        $this->command->info('✅ ' . $newCount . ' new career levels added!');
        $this->command->info('⏭️  ' . $skippedCount . ' duplicate levels skipped.');
    }

    private function getCareerLevelsList(): array
    {
        return [
            // Entry Level
            ['name' => 'Fresh Graduate', 'level_order' => 1],
            ['name' => 'Internship', 'level_order' => 2],
            ['name' => 'Management Trainee', 'level_order' => 3],
            ['name' => 'Entry Level', 'level_order' => 4],
            ['name' => 'Junior', 'level_order' => 5],

            // Mid Level
            ['name' => 'Intermediate', 'level_order' => 6],
            ['name' => 'Mid Level (1-3 years)', 'level_order' => 7],
            ['name' => 'Mid Level (3-5 years)', 'level_order' => 8],
            ['name' => 'Experienced Professional (5-7 years)', 'level_order' => 9],

            // Senior Level
            ['name' => 'Senior (7-10 years)', 'level_order' => 10],
            ['name' => 'Lead', 'level_order' => 11],
            ['name' => 'Manager', 'level_order' => 12],
            ['name' => 'Senior Manager', 'level_order' => 13],
            ['name' => 'Principal', 'level_order' => 14],

            // Executive Level
            ['name' => 'Director', 'level_order' => 15],
            ['name' => 'Vice President (VP)', 'level_order' => 16],
            ['name' => 'Executive Director', 'level_order' => 17],
            ['name' => 'Managing Director', 'level_order' => 18],
            ['name' => 'Chief Officer (CEO/CFO/CTO)', 'level_order' => 19],

            // Specialist Level
            ['name' => 'Subject Matter Expert (SME)', 'level_order' => 20],
            ['name' => 'Consultant', 'level_order' => 21],
            ['name' => 'Advisor', 'level_order' => 22],
            ['name' => 'Specialist', 'level_order' => 23],
            ['name' => 'Analyst', 'level_order' => 24],
        ];
    }
}
