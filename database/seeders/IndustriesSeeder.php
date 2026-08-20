<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use Illuminate\Support\Str;

class IndustriesSeeder extends Seeder
{
    public function run(): void
    {
        $industries = $this->getIndustriesList();
        $newCount = 0;
        $skippedCount = 0;

        foreach ($industries as $industry) {
            $slug = Str::slug($industry['name']);

            $existing = Industry::where('slug', $slug)->first();

            if ($existing) {
                $skippedCount++;
                continue;
            }

            Industry::create([
                'name' => $industry['name'],
                'slug' => $slug,
                'icon' => $industry['icon'] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $newCount++;
        }

        $this->command->info('✅ ' . $newCount . ' new industries added!');
        $this->command->info('⏭️  ' . $skippedCount . ' duplicate industries skipped.');
    }

    private function getIndustriesList(): array
    {
        return [
            // IT & Technology
            ['name' => 'Software & Web Development', 'icon' => 'fa-code'],
            ['name' => 'IT Services & Consulting', 'icon' => 'fa-laptop'],
            ['name' => 'E-commerce / Internet', 'icon' => 'fa-shopping-cart'],
            ['name' => 'Artificial Intelligence / ML', 'icon' => 'fa-brain'],
            ['name' => 'Cybersecurity', 'icon' => 'fa-shield-alt'],
            ['name' => 'Data Analytics & BI', 'icon' => 'fa-chart-bar'],
            ['name' => 'Cloud Computing', 'icon' => 'fa-cloud'],
            ['name' => 'Blockchain / Crypto', 'icon' => 'fa-link'],
            ['name' => 'Gaming & Animation', 'icon' => 'fa-gamepad'],

            // Finance
            ['name' => 'Accounting & Auditing', 'icon' => 'fa-calculator'],
            ['name' => 'Banking & Financial Services', 'icon' => 'fa-university'],
            ['name' => 'Investment Banking', 'icon' => 'fa-chart-line'],
            ['name' => 'Insurance', 'icon' => 'fa-shield'],
            ['name' => 'Asset Management', 'icon' => 'fa-coins'],
            ['name' => 'Microfinance', 'icon' => 'fa-hand-holding-usd'],
            ['name' => 'Fintech / Digital Payments', 'icon' => 'fa-mobile-alt'],
            ['name' => 'Taxation Services', 'icon' => 'fa-file-invoice-dollar'],
            ['name' => 'Stock Broking', 'icon' => 'fa-chart-pie'],
            ['name' => 'Leasing & Factoring', 'icon' => 'fa-file-signature'],

            // Sales & Marketing
            ['name' => 'Sales & Business Development', 'icon' => 'fa-handshake'],
            ['name' => 'Digital Marketing', 'icon' => 'fa-bullhorn'],
            ['name' => 'Brand Management', 'icon' => 'fa-tag'],
            ['name' => 'Market Research', 'icon' => 'fa-search'],
            ['name' => 'Advertising & PR', 'icon' => 'fa-ad'],
            ['name' => 'Event Management', 'icon' => 'fa-calendar-check'],
            ['name' => 'Media Buying', 'icon' => 'fa-tv'],
            ['name' => 'SEO / SEM', 'icon' => 'fa-google'],
            ['name' => 'Content Marketing', 'icon' => 'fa-pen-fancy'],
            ['name' => 'Affiliate Marketing', 'icon' => 'fa-link'],

            // Healthcare
            ['name' => 'Healthcare / Hospitals', 'icon' => 'fa-hospital'],
            ['name' => 'Pharmaceuticals', 'icon' => 'fa-capsules'],
            ['name' => 'Medical Devices', 'icon' => 'fa-stethoscope'],
            ['name' => 'Dental Services', 'icon' => 'fa-tooth'],
            ['name' => 'Mental Health', 'icon' => 'fa-heart'],
            ['name' => 'Telemedicine', 'icon' => 'fa-video'],
            ['name' => 'Clinical Research', 'icon' => 'fa-flask'],
            ['name' => 'Nutrition & Wellness', 'icon' => 'fa-apple-alt'],
            ['name' => 'Physiotherapy', 'icon' => 'fa-walking'],
            ['name' => 'Veterinary Services', 'icon' => 'fa-dog'],

            // Engineering & Construction
            ['name' => 'Civil Engineering', 'icon' => 'fa-drafting-compass'],
            ['name' => 'Mechanical Engineering', 'icon' => 'fa-cogs'],
            ['name' => 'Electrical Engineering', 'icon' => 'fa-bolt'],
            ['name' => 'Construction & Real Estate', 'icon' => 'fa-hard-hat'],
            ['name' => 'Oil & Gas / Energy', 'icon' => 'fa-oil-can'],
            ['name' => 'Architecture & Urban Planning', 'icon' => 'fa-city'],
            ['name' => 'Environmental Engineering', 'icon' => 'fa-leaf'],
            ['name' => 'Mining & Minerals', 'icon' => 'fa-gem'],
            ['name' => 'Geotechnical Engineering', 'icon' => 'fa-mountain'],
            ['name' => 'Structural Engineering', 'icon' => 'fa-building'],

            // Education
            ['name' => 'Education / Training', 'icon' => 'fa-graduation-cap'],
            ['name' => 'E-Learning / EdTech', 'icon' => 'fa-chalkboard-teacher'],
            ['name' => 'Higher Education (Universities)', 'icon' => 'fa-university'],
            ['name' => 'K-12 Schools', 'icon' => 'fa-school'],
            ['name' => 'Vocational Training', 'icon' => 'fa-tools'],
            ['name' => 'Test Preparation', 'icon' => 'fa-pencil-alt'],
            ['name' => 'Language Training', 'icon' => 'fa-language'],
            ['name' => 'Coaching & Tutoring', 'icon' => 'fa-user-graduate'],
            ['name' => 'Educational Consulting', 'icon' => 'fa-phone-alt'],

            // Human Resources
            ['name' => 'Human Resources / HRIS', 'icon' => 'fa-users'],
            ['name' => 'Recruitment & Talent Acquisition', 'icon' => 'fa-user-plus'],
            ['name' => 'Training & Development', 'icon' => 'fa-chalkboard'],
            ['name' => 'Payroll & Benefits', 'icon' => 'fa-money-bill-wave'],
            ['name' => 'Performance Management', 'icon' => 'fa-tasks'],
            ['name' => 'Organizational Development', 'icon' => 'fa-sitemap'],
            ['name' => 'Employee Engagement', 'icon' => 'fa-smile'],
            ['name' => 'HR Tech / HRMS', 'icon' => 'fa-database'],

            // Logistics
            ['name' => 'Distribution & Logistics', 'icon' => 'fa-truck'],
            ['name' => 'Supply Chain Management', 'icon' => 'fa-boxes'],
            ['name' => 'Freight & Cargo', 'icon' => 'fa-ship'],
            ['name' => 'Warehousing', 'icon' => 'fa-warehouse'],
            ['name' => 'Courier & Delivery Services', 'icon' => 'fa-box'],
            ['name' => 'Procurement', 'icon' => 'fa-shopping-bag'],
            ['name' => 'Import / Export', 'icon' => 'fa-globe'],
            ['name' => 'Fleet Management', 'icon' => 'fa-car'],
            ['name' => 'Inventory Management', 'icon' => 'fa-clipboard-list'],

            // Retail
            ['name' => 'Retail', 'icon' => 'fa-store'],
            ['name' => 'Wholesale Trade', 'icon' => 'fa-warehouse'],
            ['name' => 'E-commerce Retail', 'icon' => 'fa-shopping-bag'],
            ['name' => 'Supermarkets / Hypermarkets', 'icon' => 'fa-shopping-cart'],
            ['name' => 'Luxury Retail', 'icon' => 'fa-gem'],
            ['name' => 'Convenience Stores', 'icon' => 'fa-store-alt'],
            ['name' => 'Franchise Operations', 'icon' => 'fa-copy'],
            ['name' => 'Department Stores', 'icon' => 'fa-building'],

            // Manufacturing
            ['name' => 'Textile & Apparel', 'icon' => 'fa-tshirt'],
            ['name' => 'FMCG', 'icon' => 'fa-box-open'],
            ['name' => 'Automobile & Auto Parts', 'icon' => 'fa-car'],
            ['name' => 'Electronics Manufacturing', 'icon' => 'fa-microchip'],
            ['name' => 'Steel & Metal', 'icon' => 'fa-industry'],
            ['name' => 'Cement & Construction Materials', 'icon' => 'fa-cubes'],
            ['name' => 'Food Processing', 'icon' => 'fa-utensils'],
            ['name' => 'Chemical / Petrochemical', 'icon' => 'fa-flask'],
            ['name' => 'Plastics & Rubber', 'icon' => 'fa-recycle'],
            ['name' => 'Paper & Packaging', 'icon' => 'fa-file'],
            ['name' => 'Glass & Ceramics', 'icon' => 'fa-wine-glass'],
            ['name' => 'Furniture & Woodwork', 'icon' => 'fa-chair'],
            ['name' => 'Jewelry & Accessories', 'icon' => 'fa-gem'],

            // Media
            ['name' => 'Media & Broadcasting', 'icon' => 'fa-broadcast'],
            ['name' => 'Journalism / Content Writing', 'icon' => 'fa-newspaper'],
            ['name' => 'Print & Publishing', 'icon' => 'fa-book'],
            ['name' => 'Public Relations', 'icon' => 'fa-bullhorn'],
            ['name' => 'TV / Film Production', 'icon' => 'fa-video'],
            ['name' => 'Radio', 'icon' => 'fa-microphone'],
            ['name' => 'Podcasting', 'icon' => 'fa-podcast'],
            ['name' => 'News Agencies', 'icon' => 'fa-rss'],
            ['name' => 'Social Media Management', 'icon' => 'fa-share-alt'],
            ['name' => 'Digital Content Creation', 'icon' => 'fa-edit'],

            // Hospitality
            ['name' => 'Hospitality & Tourism', 'icon' => 'fa-hotel'],
            ['name' => 'Hotels / Resorts', 'icon' => 'fa-bed'],
            ['name' => 'Restaurants & Catering', 'icon' => 'fa-utensils'],
            ['name' => 'Travel Agencies', 'icon' => 'fa-plane'],
            ['name' => 'Airlines / Aviation', 'icon' => 'fa-plane-departure'],
            ['name' => 'Cruise Industry', 'icon' => 'fa-ship'],
            ['name' => 'Event Planning', 'icon' => 'fa-calendar-alt'],
            ['name' => 'Tourism Development', 'icon' => 'fa-map-marked-alt'],
            ['name' => 'Food & Beverage', 'icon' => 'fa-coffee'],

            // Government
            ['name' => 'Government / Civil Services', 'icon' => 'fa-landmark'],
            ['name' => 'Public Administration', 'icon' => 'fa-city'],
            ['name' => 'Defense / Military', 'icon' => 'fa-shield-alt'],
            ['name' => 'Law Enforcement', 'icon' => 'fa-police'],
            ['name' => 'Regulatory Bodies', 'icon' => 'fa-gavel'],
            ['name' => 'State-owned Enterprises', 'icon' => 'fa-building'],
            ['name' => 'Municipal Services', 'icon' => 'fa-trash'],
            ['name' => 'Diplomacy / Foreign Affairs', 'icon' => 'fa-globe-americas'],

            // Legal
            ['name' => 'Legal Services', 'icon' => 'fa-gavel'],
            ['name' => 'Corporate Law', 'icon' => 'fa-building'],
            ['name' => 'Criminal Law', 'icon' => 'fa-handcuffs'],
            ['name' => 'Intellectual Property', 'icon' => 'fa-copyright'],
            ['name' => 'Family Law', 'icon' => 'fa-users'],
            ['name' => 'Compliance & Risk', 'icon' => 'fa-check-double'],
            ['name' => 'Legal Tech', 'icon' => 'fa-laptop'],

            // Creative
            ['name' => 'Creative Design', 'icon' => 'fa-palette'],
            ['name' => 'Graphic Design', 'icon' => 'fa-paint-brush'],
            ['name' => 'UI/UX Design', 'icon' => 'fa-mouse-pointer'],
            ['name' => 'Interior Design', 'icon' => 'fa-couch'],
            ['name' => 'Fashion Design', 'icon' => 'fa-tshirt'],
            ['name' => 'Photography', 'icon' => 'fa-camera'],
            ['name' => 'Video Production', 'icon' => 'fa-film'],
            ['name' => '3D / Animation', 'icon' => 'fa-cube'],
            ['name' => 'Product Design', 'icon' => 'fa-pencil-ruler'],
            ['name' => 'Art / Fine Arts', 'icon' => 'fa-paint-brush'],

            // Agriculture
            ['name' => 'Agriculture / Farming', 'icon' => 'fa-tractor'],
            ['name' => 'AgriTech', 'icon' => 'fa-leaf'],
            ['name' => 'Dairy & Livestock', 'icon' => 'fa-cow'],
            ['name' => 'Fisheries', 'icon' => 'fa-fish'],
            ['name' => 'Forestry', 'icon' => 'fa-tree'],
            ['name' => 'Horticulture', 'icon' => 'fa-seedling'],
            ['name' => 'Organic Farming', 'icon' => 'fa-leaf'],
            ['name' => 'Agricultural Equipment', 'icon' => 'fa-tools'],
            ['name' => 'Agro-processing', 'icon' => 'fa-industry'],
            ['name' => 'Irrigation / Water Management', 'icon' => 'fa-water'],

            // Customer Support
            ['name' => 'Customer Service', 'icon' => 'fa-headset'],
            ['name' => 'Technical Support', 'icon' => 'fa-tools'],
            ['name' => 'Call Center Operations', 'icon' => 'fa-phone'],
            ['name' => 'BPO', 'icon' => 'fa-building'],
            ['name' => 'KPO', 'icon' => 'fa-brain'],
            ['name' => 'Help Desk Support', 'icon' => 'fa-life-ring'],
            ['name' => 'Client Retention', 'icon' => 'fa-handshake'],
            ['name' => 'Complaint Handling', 'icon' => 'fa-comment-dots'],

            // Professional Services
            ['name' => 'Management Consulting', 'icon' => 'fa-briefcase'],
            ['name' => 'IT Consulting', 'icon' => 'fa-laptop-code'],
            ['name' => 'Business Consulting', 'icon' => 'fa-chart-line'],
            ['name' => 'Strategy Consulting', 'icon' => 'fa-chess-king'],
            ['name' => 'HR Consulting', 'icon' => 'fa-users-cog'],
            ['name' => 'Financial Advisory', 'icon' => 'fa-hand-holding-usd'],
            ['name' => 'Audit & Assurance', 'icon' => 'fa-check-circle'],

            // Real Estate
            ['name' => 'Real Estate Development', 'icon' => 'fa-building'],
            ['name' => 'Property Management', 'icon' => 'fa-home'],
            ['name' => 'Real Estate Brokerage', 'icon' => 'fa-handshake'],
            ['name' => 'Commercial Real Estate', 'icon' => 'fa-building'],
            ['name' => 'Residential Real Estate', 'icon' => 'fa-house'],
            ['name' => 'Real Estate Investment', 'icon' => 'fa-chart-line'],
            ['name' => 'PropTech', 'icon' => 'fa-mobile-alt'],

            // Transport
            ['name' => 'Transport & Logistics', 'icon' => 'fa-truck'],
            ['name' => 'Airlines', 'icon' => 'fa-plane'],
            ['name' => 'Railways', 'icon' => 'fa-train'],
            ['name' => 'Shipping / Maritime', 'icon' => 'fa-ship'],
            ['name' => 'Road Transport', 'icon' => 'fa-bus'],
            ['name' => 'Public Transport', 'icon' => 'fa-subway'],
            ['name' => 'Ride-hailing', 'icon' => 'fa-taxi'],
            ['name' => 'Vehicle Rental', 'icon' => 'fa-car'],

            // Entertainment
            ['name' => 'Entertainment & Media', 'icon' => 'fa-tv'],
            ['name' => 'Film Industry', 'icon' => 'fa-film'],
            ['name' => 'Music Industry', 'icon' => 'fa-music'],
            ['name' => 'Gaming', 'icon' => 'fa-gamepad'],
            ['name' => 'Live Events', 'icon' => 'fa-calendar'],
            ['name' => 'OTT Platforms', 'icon' => 'fa-play-circle'],
            ['name' => 'Sports Management', 'icon' => 'fa-futbol'],

            // Non-Profit
            ['name' => 'Non-Profit Organizations', 'icon' => 'fa-hand-holding-heart'],
            ['name' => 'Social Services', 'icon' => 'fa-people-arrows'],
            ['name' => 'Community Development', 'icon' => 'fa-city'],
            ['name' => 'Humanitarian Relief', 'icon' => 'fa-first-aid'],
            ['name' => 'NGO / INGO', 'icon' => 'fa-globe'],
            ['name' => 'Social Enterprise', 'icon' => 'fa-store'],
            ['name' => 'Environment / Climate', 'icon' => 'fa-leaf'],
            ['name' => 'Human Rights', 'icon' => 'fa-scale-balanced'],
            ['name' => 'Women\'s Empowerment', 'icon' => 'fa-venus'],
            ['name' => 'Child Welfare', 'icon' => 'fa-child'],

            // Research
            ['name' => 'R&D / Scientific Research', 'icon' => 'fa-flask'],
            ['name' => 'Market Research', 'icon' => 'fa-search'],
            ['name' => 'Social Research', 'icon' => 'fa-users'],
            ['name' => 'Policy Research', 'icon' => 'fa-file-alt'],
            ['name' => 'Academic Research', 'icon' => 'fa-graduation-cap'],
            ['name' => 'Scientific Laboratories', 'icon' => 'fa-microscope'],
            ['name' => 'Innovation Centers', 'icon' => 'fa-lightbulb'],

            // Security
            ['name' => 'Security Services', 'icon' => 'fa-shield-alt'],
            ['name' => 'Private Security', 'icon' => 'fa-user-secret'],
            ['name' => 'Cyber Security', 'icon' => 'fa-shield-halved'],
            ['name' => 'Defense / Military', 'icon' => 'fa-shield'],
            ['name' => 'Emergency Services', 'icon' => 'fa-ambulance'],
            ['name' => 'Fire Safety', 'icon' => 'fa-fire-extinguisher'],
            ['name' => 'Disaster Management', 'icon' => 'fa-umbrella'],

            // Telecom
            ['name' => 'Telecom / Mobile Networks', 'icon' => 'fa-signal'],
            ['name' => 'Internet Service Providers', 'icon' => 'fa-wifi'],
            ['name' => 'Fiber Optics', 'icon' => 'fa-circle-nodes'],
            ['name' => '5G / Wireless', 'icon' => 'fa-satellite'],
            ['name' => 'VoIP Services', 'icon' => 'fa-phone'],
            ['name' => 'Telecom Infrastructure', 'icon' => 'fa-tower-cell'],
            ['name' => 'Mobile Apps / VAS', 'icon' => 'fa-mobile-screen-button'],

            // Energy
            ['name' => 'Electricity / Power', 'icon' => 'fa-bolt'],
            ['name' => 'Oil & Gas', 'icon' => 'fa-oil-can'],
            ['name' => 'Renewable Energy', 'icon' => 'fa-sun'],
            ['name' => 'Water & Sanitation', 'icon' => 'fa-water'],
            ['name' => 'Nuclear Energy', 'icon' => 'fa-atom'],
            ['name' => 'Energy Trading', 'icon' => 'fa-chart-line'],
            ['name' => 'Smart Grids', 'icon' => 'fa-microchip'],

            // E-commerce
            ['name' => 'E-commerce Platforms', 'icon' => 'fa-shopping-cart'],
            ['name' => 'Online Retail', 'icon' => 'fa-store'],
            ['name' => 'Dropshipping', 'icon' => 'fa-box'],
            ['name' => 'Marketplace', 'icon' => 'fa-store-alt'],
            ['name' => 'Payment Gateways', 'icon' => 'fa-credit-card'],
            ['name' => 'Digital Stores', 'icon' => 'fa-laptop'],

            // Biotechnology
            ['name' => 'Biotech R&D', 'icon' => 'fa-flask'],
            ['name' => 'Genetic Engineering', 'icon' => 'fa-dna'],
            ['name' => 'Bioinformatics', 'icon' => 'fa-microscope'],
            ['name' => 'Agricultural Biotech', 'icon' => 'fa-seedling'],
            ['name' => 'Medical Biotech', 'icon' => 'fa-syringe'],
            ['name' => 'Industrial Biotech', 'icon' => 'fa-industry'],
            ['name' => 'Stem Cell Research', 'icon' => 'fa-dna'],

            // Sports
            ['name' => 'Sports Management', 'icon' => 'fa-futbol'],
            ['name' => 'Fitness Centers / Gyms', 'icon' => 'fa-dumbbell'],
            ['name' => 'Sports Events', 'icon' => 'fa-trophy'],
            ['name' => 'Professional Sports Teams', 'icon' => 'fa-users'],
            ['name' => 'Sports Marketing', 'icon' => 'fa-bullhorn'],
            ['name' => 'Health Clubs', 'icon' => 'fa-heart'],
            ['name' => 'Yoga / Wellness Studios', 'icon' => 'fa-spa'],
            ['name' => 'Sports Equipment', 'icon' => 'fa-basketball'],

            // Textile (Additional)
            ['name' => 'Textile & Apparel', 'icon' => 'fa-tshirt'],
            ['name' => 'Fashion Design', 'icon' => 'fa-palette'],
            ['name' => 'Garment Manufacturing', 'icon' => 'fa-industry'],
            ['name' => 'Fabric Production', 'icon' => 'fa-layer-group'],
            ['name' => 'Dyeing & Finishing', 'icon' => 'fa-tint'],
            ['name' => 'Branded Clothing', 'icon' => 'fa-tag'],
            ['name' => 'Bridal Fashion', 'icon' => 'fa-crown'],
            ['name' => 'Textile Export', 'icon' => 'fa-globe'],
        ];
    }
}
