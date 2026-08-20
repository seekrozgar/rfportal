<?php
// database/seeders/PackagesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackagesSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            // ==================== EMPLOYER PACKAGES ====================
            [
                'name' => 'Basic Plan',
                'type' => 'employer',
                'price' => 4999,
                'duration_days' => 30,
                'job_posts_limit' => 5,
                'features' => [
                    'Post 5 Jobs',
                    '30 Days Validity',
                    'Basic Job Listing',
                    'Email Support',
                    'Application Tracking'
                ],
                'is_featured' => false,
                'badge_color' => '#6c757d',
                'display_order' => 1,
                'description' => 'Perfect for small businesses and startups.'
            ],
            [
                'name' => 'Pro Plan',
                'type' => 'employer',
                'price' => 9999,
                'duration_days' => 60,
                'job_posts_limit' => 15,
                'features' => [
                    'Post 15 Jobs',
                    '60 Days Validity',
                    'Featured Job Listing',
                    'Priority Support',
                    'Application Tracking',
                    'Candidate Search',
                    'Email Alerts',
                    'Company Profile Boost'
                ],
                'is_featured' => true,
                'badge_color' => '#ffc107',
                'display_order' => 2,
                'description' => 'Best for growing companies with regular hiring needs.'
            ],
            [
                'name' => 'Enterprise Plan',
                'type' => 'employer',
                'price' => 24999,
                'duration_days' => 90,
                'job_posts_limit' => 50,
                'features' => [
                    'Post 50 Jobs',
                    '90 Days Validity',
                    'Premium Job Listing',
                    '24/7 Priority Support',
                    'Application Tracking',
                    'Advanced Candidate Search',
                    'Email Alerts',
                    'Company Profile Boost',
                    'Multiple User Access',
                    'Dedicated Account Manager',
                    'Custom Branding',
                    'API Access'
                ],
                'is_featured' => false,
                'badge_color' => '#28a745',
                'display_order' => 3,
                'description' => 'For large organizations with high-volume hiring.'
            ],

            // ==================== SEEKER PACKAGES ====================
            [
                'name' => 'Free Plan',
                'type' => 'seeker',
                'price' => 0,
                'duration_days' => 365,
                'resume_views_limit' => 10,
                'features' => [
                    '10 Resume Views per Month',
                    'Basic Job Search',
                    'Save Jobs (5)',
                    'Job Alerts',
                    'Single Resume'
                ],
                'is_featured' => false,
                'badge_color' => '#6c757d',
                'display_order' => 1,
                'description' => 'Free plan for job seekers.'
            ],
            [
                'name' => 'Pro Seeker Plan',
                'type' => 'seeker',
                'price' => 2999,
                'duration_days' => 30,
                'resume_views_limit' => 100,
                'features' => [
                    '100 Resume Views per Month',
                    'Advanced Job Search',
                    'Save Unlimited Jobs',
                    'Priority Job Alerts',
                    'Multiple Resumes (5)',
                    'Resume Highlight',
                    'Application Tracking',
                    'Job Recommendations',
                    'Hidden Contact Number',
                    'Premium Badge'
                ],
                'is_featured' => true,
                'badge_color' => '#ffc107',
                'display_order' => 2,
                'description' => 'Boost your job search with premium features.'
            ],
            [
                'name' => 'Elite Seeker Plan',
                'type' => 'seeker',
                'price' => 5999,
                'duration_days' => 60,
                'resume_views_limit' => 300,
                'features' => [
                    '300 Resume Views per Month',
                    'Advanced Job Search',
                    'Save Unlimited Jobs',
                    'Priority Job Alerts',
                    'Multiple Resumes (10)',
                    'Resume Highlight',
                    'Application Tracking',
                    'Job Recommendations',
                    'Hidden Contact Number',
                    'Premium Badge',
                    'Priority Application',
                    'Career Coaching',
                    'Interview Preparation',
                    'Access to Premium Jobs'
                ],
                'is_featured' => false,
                'badge_color' => '#28a745',
                'display_order' => 3,
                'description' => 'Complete career growth package.'
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        $this->command->info('✅ ' . count($packages) . ' packages seeded successfully!');
    }
}
