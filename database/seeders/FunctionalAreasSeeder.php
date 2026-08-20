<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FunctionalArea;
use Illuminate\Support\Str;

class FunctionalAreasSeeder extends Seeder
{
    public function run(): void
    {
        $areas = $this->getFunctionalAreasList();
        $newCount = 0;
        $skippedCount = 0;

        foreach ($areas as $area) {
            $slug = Str::slug($area['name']);

            $existing = FunctionalArea::where('slug', $slug)->first();

            if ($existing) {
                $skippedCount++;
                continue;
            }

            FunctionalArea::create([
                'name' => $area['name'],
                'slug' => $slug,
                'industry_id' => $area['industry_id'] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $newCount++;
        }

        $this->command->info('✅ ' . $newCount . ' new functional areas added!');
        $this->command->info('⏭️  ' . $skippedCount . ' duplicate areas skipped.');
    }

    private function getFunctionalAreasList(): array
    {
        return [
            // Accounts, Finance & Banking
            ['name' => 'Accounts / Finance / Audit'],
            ['name' => 'Banking / Financial Services'],
            ['name' => 'Investment Banking / Asset Management'],
            ['name' => 'Taxation'],
            ['name' => 'Treasury Management'],
            ['name' => 'Corporate Finance'],
            ['name' => 'Credit Analysis'],
            ['name' => 'Insurance'],
            ['name' => 'Microfinance'],
            ['name' => 'Fintech / Digital Payments'],
            ['name' => 'Risk Management'],
            ['name' => 'Compliance'],

            // Administration & Management
            ['name' => 'Administration / Office Management'],
            ['name' => 'Executive Management / CEO'],
            ['name' => 'Facilities Management'],
            ['name' => 'Corporate Affairs'],
            ['name' => 'Strategic Planning'],
            ['name' => 'Project Management'],
            ['name' => 'Operations Management'],
            ['name' => 'Business Development'],
            ['name' => 'Vendor Management'],
            ['name' => 'Documentation / Record Keeping'],

            // IT & Software
            ['name' => 'Software & Web Development'],
            ['name' => 'Systems Analyst'],
            ['name' => 'Database Administrator (DBA)'],
            ['name' => 'Network Administration'],
            ['name' => 'IT Support / Helpdesk'],
            ['name' => 'Cybersecurity'],
            ['name' => 'Cloud Computing'],
            ['name' => 'DevOps / SRE'],
            ['name' => 'Data Analytics / BI'],
            ['name' => 'AI / Machine Learning'],
            ['name' => 'Quality Assurance / Testing'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Product Management (Tech)'],

            // Sales & Marketing
            ['name' => 'Sales & Business Development'],
            ['name' => 'Digital Marketing'],
            ['name' => 'Brand Management'],
            ['name' => 'Market Research'],
            ['name' => 'Advertising / PR'],
            ['name' => 'Social Media Management'],
            ['name' => 'SEO / SEM'],
            ['name' => 'Content Marketing'],
            ['name' => 'Affiliate Marketing'],
            ['name' => 'Telemarketing / Tele-sales'],
            ['name' => 'Account Management'],
            ['name' => 'Sales Strategy & Planning'],

            // Human Resources
            ['name' => 'Human Resources'],
            ['name' => 'Recruitment / Talent Acquisition'],
            ['name' => 'Training & Development'],
            ['name' => 'Performance Management'],
            ['name' => 'Compensation & Benefits'],
            ['name' => 'HRIS / HRMS'],
            ['name' => 'Organizational Development'],
            ['name' => 'Employee Engagement'],
            ['name' => 'Learning & Development (L&D)'],
            ['name' => 'Industrial Relations'],

            // Engineering & Technical
            ['name' => 'Civil Engineering'],
            ['name' => 'Mechanical Engineering'],
            ['name' => 'Electrical Engineering'],
            ['name' => 'Chemical Engineering'],
            ['name' => 'Oil & Gas / Energy'],
            ['name' => 'Maintenance / Repairs'],
            ['name' => 'Quality Control'],
            ['name' => 'Health, Safety & Environment (HSE)'],
            ['name' => 'Architecture'],
            ['name' => 'Urban Planning'],
            ['name' => 'Geotechnical Engineering'],
            ['name' => 'Environmental Engineering'],
            ['name' => 'Mining & Minerals'],

            // Healthcare
            ['name' => 'Healthcare / Medical'],
            ['name' => 'Nursing'],
            ['name' => 'Pharmaceuticals'],
            ['name' => 'Medical Billing / Coding'],
            ['name' => 'Health Informatics'],
            ['name' => 'Clinical Research'],
            ['name' => 'Public Health'],
            ['name' => 'Nutrition & Dietetics'],
            ['name' => 'Physiotherapy / Rehabilitation'],
            ['name' => 'Dentistry'],
            ['name' => 'Veterinary Services'],
            ['name' => 'Mental Health / Psychology'],

            // Manufacturing & Production
            ['name' => 'Production / Manufacturing'],
            ['name' => 'Supply Chain Management'],
            ['name' => 'Procurement / Purchasing'],
            ['name' => 'Inventory Management'],
            ['name' => 'Warehouse & Distribution'],
            ['name' => 'Logistics'],
            ['name' => 'Quality Assurance (QA)'],
            ['name' => 'Production Planning'],
            ['name' => 'Plant Operations'],
            ['name' => 'Lean Manufacturing / Six Sigma'],
            ['name' => 'Textile / Garment Manufacturing'],
            ['name' => 'Food Processing'],

            // Education
            ['name' => 'Education / Teaching'],
            ['name' => 'E-Learning / EdTech'],
            ['name' => 'Higher Education'],
            ['name' => 'Curriculum Development'],
            ['name' => 'Educational Consulting'],
            ['name' => 'Test Preparation / Coaching'],
            ['name' => 'Language Training'],
            ['name' => 'Special Education'],
            ['name' => 'Academic Administration'],

            // Creative & Design
            ['name' => 'Creative Design'],
            ['name' => 'Graphic Design'],
            ['name' => 'Fashion Design'],
            ['name' => 'Interior Design'],
            ['name' => 'Photography / Videography'],
            ['name' => 'Animation / 3D Design'],
            ['name' => 'Product Design'],
            ['name' => 'Art Direction'],
            ['name' => 'Multimedia'],

            // Customer Support
            ['name' => 'Customer Service'],
            ['name' => 'Technical Support'],
            ['name' => 'Call Center Operations'],
            ['name' => 'Help Desk Support'],
            ['name' => 'Client Retention'],
            ['name' => 'Complaint Handling'],
            ['name' => 'SLA Management'],
            ['name' => 'CRM Management'],

            // Media & Communication
            ['name' => 'Media & Broadcasting'],
            ['name' => 'Journalism / Content Writing'],
            ['name' => 'Public Relations'],
            ['name' => 'Print & Publishing'],
            ['name' => 'Video Production'],
            ['name' => 'News / Reporting'],
            ['name' => 'Podcast / Radio'],

            // Legal
            ['name' => 'Legal Affairs'],
            ['name' => 'Corporate Law'],
            ['name' => 'Intellectual Property'],
            ['name' => 'Company Secretarial'],
            ['name' => 'Regulatory Affairs'],
            ['name' => 'Legal Tech'],

            // Professional Services
            ['name' => 'Management Consulting'],
            ['name' => 'IT Consulting'],
            ['name' => 'Business Consulting'],
            ['name' => 'Strategy Consulting'],
            ['name' => 'HR Consulting'],
            ['name' => 'Financial Advisory'],
            ['name' => 'Audit & Assurance'],

            // Real Estate & Construction
            ['name' => 'Real Estate Development'],
            ['name' => 'Property Management'],
            ['name' => 'Construction Management'],
            ['name' => 'Facility Management'],
            ['name' => 'Real Estate Brokerage'],
            ['name' => 'PropTech'],

            // Agriculture & Environment
            ['name' => 'Agriculture / Farming'],
            ['name' => 'AgriTech'],
            ['name' => 'Environmental Management'],
            ['name' => 'Forestry / Wildlife'],
            ['name' => 'Fisheries / Aquaculture'],
            ['name' => 'Water Resource Management'],
            ['name' => 'Climate Change / Sustainability'],

            // Planning & Development
            ['name' => 'Corporate Planning & Development'],
            ['name' => 'Business Strategy'],
            ['name' => 'Policy Planning'],
            ['name' => 'Economic Development'],
            ['name' => 'Urban Planning'],
            ['name' => 'Regional Planning'],

            // Logistics & Supply Chain
            ['name' => 'Distribution'],
            ['name' => 'Fleet Management'],
            ['name' => 'Import / Export'],
            ['name' => 'Inventory Control'],
            ['name' => 'Freight / Cargo'],

            // Security & Defense
            ['name' => 'Security Management'],
            ['name' => 'Intelligence'],
            ['name' => 'Defense / Military'],
            ['name' => 'Emergency Management'],
            ['name' => 'Fire Safety'],

            // Hospitality & Tourism
            ['name' => 'Hospitality Management'],
            ['name' => 'Hotel / Resort Management'],
            ['name' => 'Travel / Tourism'],
            ['name' => 'Restaurant / Catering'],
            ['name' => 'Event Management'],
            ['name' => 'Food & Beverage'],

            // Energy & Utilities
            ['name' => 'Power / Electricity'],
            ['name' => 'Renewable Energy'],
            ['name' => 'Water Management'],
            ['name' => 'Nuclear Energy'],
            ['name' => 'Energy Trading'],

            // Retail
            ['name' => 'Retail Management'],
            ['name' => 'Fashion Retail'],
            ['name' => 'E-commerce Retail'],
            ['name' => 'Store Operations'],
            ['name' => 'Visual Merchandising'],
            ['name' => 'Luxury Retail'],
            ['name' => 'Franchise Management'],

            // Non-Profit
            ['name' => 'NGOs / INGOs'],
            ['name' => 'Social Services'],
            ['name' => 'Community Development'],
            ['name' => 'Humanitarian Relief'],
            ['name' => 'Social Enterprise'],
            ['name' => 'Human Rights'],

            // Entertainment & Media
            ['name' => 'Entertainment / Media'],
            ['name' => 'Film / TV Production'],
            ['name' => 'Gaming / Animation'],
            ['name' => 'Music / Performing Arts'],
            ['name' => 'Sports Management'],
            ['name' => 'Live Events'],

            // Data & Analytics
            ['name' => 'Data Science'],
            ['name' => 'Statistics / Research'],
            ['name' => 'Big Data'],
            ['name' => 'Data Engineering'],
        ];
    }
}
