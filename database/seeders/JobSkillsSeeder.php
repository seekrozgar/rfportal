<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use Illuminate\Support\Str;

class JobSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = $this->getSkillsList();
        $newCount = 0;
        $skippedCount = 0;

        foreach ($skills as $skill) {
            $slug = Str::slug($skill['name']);

            // ✅ Check if skill already exists
            $existing = Skill::where('slug', $slug)->first();

            if ($existing) {
                $skippedCount++;
                continue; // Skip duplicate
            }

            Skill::create([
                'name' => $skill['name'],
                'slug' => $slug,
                'category' => $skill['category'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $newCount++;
        }

        $this->command->info('✅ ' . $newCount . ' new skills added!');
        $this->command->info('⏭️  ' . $skippedCount . ' duplicate skills skipped.');
    }

    private function getSkillsList(): array
    {
        return [
            // ============================================
            // 💻 PROGRAMMING LANGUAGES & DEVELOPMENT
            // ============================================
            ['name' => 'Python', 'category' => 'programming'],
            ['name' => 'JavaScript', 'category' => 'programming'],
            ['name' => 'TypeScript', 'category' => 'programming'],
            ['name' => 'Java', 'category' => 'programming'],
            ['name' => 'C# / .NET', 'category' => 'programming'],
            ['name' => 'PHP', 'category' => 'programming'],
            ['name' => 'C++', 'category' => 'programming'],
            ['name' => 'Ruby', 'category' => 'programming'],
            ['name' => 'Go / Golang', 'category' => 'programming'],
            ['name' => 'Rust', 'category' => 'programming'],
            ['name' => 'Kotlin', 'category' => 'programming'],
            ['name' => 'Swift', 'category' => 'programming'],
            ['name' => 'R', 'category' => 'programming'],
            ['name' => 'Scala', 'category' => 'programming'],

            // ============================================
            // 🎨 FRONTEND DEVELOPMENT
            // ============================================
            ['name' => 'HTML5', 'category' => 'frontend'],
            ['name' => 'CSS3', 'category' => 'frontend'],
            ['name' => 'SASS / SCSS', 'category' => 'frontend'],
            ['name' => 'Tailwind CSS', 'category' => 'frontend'],
            ['name' => 'Bootstrap', 'category' => 'frontend'],
            ['name' => 'React.js', 'category' => 'frontend'],
            ['name' => 'Next.js', 'category' => 'frontend'],
            ['name' => 'Angular', 'category' => 'frontend'],
            ['name' => 'Vue.js', 'category' => 'frontend'],
            ['name' => 'jQuery', 'category' => 'frontend'],
            ['name' => 'AJAX', 'category' => 'frontend'],
            ['name' => 'Vite', 'category' => 'frontend'],
            ['name' => 'Redux', 'category' => 'frontend'],
            ['name' => 'Zustand', 'category' => 'frontend'],

            // ============================================
            // ⚙️ BACKEND DEVELOPMENT
            // ============================================
            ['name' => 'Node.js', 'category' => 'backend'],
            ['name' => 'Express.js', 'category' => 'backend'],
            ['name' => 'Laravel', 'category' => 'backend'],
            ['name' => 'Spring Boot', 'category' => 'backend'],
            ['name' => 'Django', 'category' => 'backend'],
            ['name' => 'Flask', 'category' => 'backend'],
            ['name' => 'FastAPI', 'category' => 'backend'],
            ['name' => '.NET Core', 'category' => 'backend'],
            ['name' => 'Ruby on Rails', 'category' => 'backend'],
            ['name' => 'NestJS', 'category' => 'backend'],
            ['name' => 'GraphQL', 'category' => 'backend'],
            ['name' => 'REST APIs', 'category' => 'backend'],
            ['name' => 'Microservices', 'category' => 'backend'],
            ['name' => 'WebSockets', 'category' => 'backend'],
            ['name' => 'Redis', 'category' => 'backend'],

            // ============================================
            // 🗄️ DATABASE & DATA STORAGE
            // ============================================
            ['name' => 'MySQL', 'category' => 'database'],
            ['name' => 'PostgreSQL', 'category' => 'database'],
            ['name' => 'MS SQL Server', 'category' => 'database'],
            ['name' => 'Oracle Database', 'category' => 'database'],
            ['name' => 'MongoDB', 'category' => 'database'],
            ['name' => 'Firebase', 'category' => 'database'],
            ['name' => 'Amazon DynamoDB', 'category' => 'database'],
            ['name' => 'Elasticsearch', 'category' => 'database'],
            ['name' => 'Cassandra', 'category' => 'database'],
            ['name' => 'SQLite', 'category' => 'database'],
            ['name' => 'Redis Cache', 'category' => 'database'],
            ['name' => 'Database Design', 'category' => 'database'],
            ['name' => 'Database Optimization', 'category' => 'database'],
            ['name' => 'Data Warehousing', 'category' => 'database'],
            ['name' => 'ETL Processes', 'category' => 'database'],

            // ============================================
            // ☁️ CLOUD & DEVOPS
            // ============================================
            ['name' => 'AWS (Amazon Web Services)', 'category' => 'cloud_devops'],
            ['name' => 'Microsoft Azure', 'category' => 'cloud_devops'],
            ['name' => 'Google Cloud Platform (GCP)', 'category' => 'cloud_devops'],
            ['name' => 'Docker', 'category' => 'cloud_devops'],
            ['name' => 'Kubernetes', 'category' => 'cloud_devops'],
            ['name' => 'Terraform', 'category' => 'cloud_devops'],
            ['name' => 'Jenkins', 'category' => 'cloud_devops'],
            ['name' => 'GitHub Actions', 'category' => 'cloud_devops'],
            ['name' => 'GitLab CI/CD', 'category' => 'cloud_devops'],
            ['name' => 'Ansible', 'category' => 'cloud_devops'],
            ['name' => 'Chef', 'category' => 'cloud_devops'],
            ['name' => 'Puppet', 'category' => 'cloud_devops'],
            ['name' => 'Linux Administration', 'category' => 'cloud_devops'],
            ['name' => 'Shell Scripting', 'category' => 'cloud_devops'],
            ['name' => 'Infrastructure as Code (IaC)', 'category' => 'cloud_devops'],

            // ============================================
            // 🤖 AI / MACHINE LEARNING / DATA SCIENCE
            // ============================================
            ['name' => 'Machine Learning', 'category' => 'ai_data'],
            ['name' => 'Deep Learning', 'category' => 'ai_data'],
            ['name' => 'Natural Language Processing (NLP)', 'category' => 'ai_data'],
            ['name' => 'Computer Vision', 'category' => 'ai_data'],
            ['name' => 'Generative AI (LLMs)', 'category' => 'ai_data'],
            ['name' => 'OpenAI / ChatGPT API', 'category' => 'ai_data'],
            ['name' => 'Hugging Face Transformers', 'category' => 'ai_data'],
            ['name' => 'PyTorch', 'category' => 'ai_data'],
            ['name' => 'TensorFlow', 'category' => 'ai_data'],
            ['name' => 'Keras', 'category' => 'ai_data'],
            ['name' => 'Scikit-learn', 'category' => 'ai_data'],
            ['name' => 'Pandas', 'category' => 'ai_data'],
            ['name' => 'NumPy', 'category' => 'ai_data'],
            ['name' => 'Data Visualization', 'category' => 'ai_data'],
            ['name' => 'Statistical Analysis', 'category' => 'ai_data'],
            ['name' => 'Data Science', 'category' => 'ai_data'],
            ['name' => 'Big Data', 'category' => 'ai_data'],
            ['name' => 'Apache Hadoop', 'category' => 'ai_data'],
            ['name' => 'Apache Spark', 'category' => 'ai_data'],

            // ============================================
            // 🧪 SOFTWARE TESTING & QA
            // ============================================
            ['name' => 'Manual Testing', 'category' => 'testing'],
            ['name' => 'Automation Testing', 'category' => 'testing'],
            ['name' => 'Selenium', 'category' => 'testing'],
            ['name' => 'Cypress', 'category' => 'testing'],
            ['name' => 'Playwright', 'category' => 'testing'],
            ['name' => 'JUnit', 'category' => 'testing'],
            ['name' => 'TestNG', 'category' => 'testing'],
            ['name' => 'BDD / Cucumber', 'category' => 'testing'],
            ['name' => 'Postman', 'category' => 'testing'],
            ['name' => 'JMeter', 'category' => 'testing'],
            ['name' => 'Load Testing', 'category' => 'testing'],
            ['name' => 'Performance Testing', 'category' => 'testing'],
            ['name' => 'Security Testing', 'category' => 'testing'],
            ['name' => 'ISTQB Certified', 'category' => 'testing'],
            ['name' => 'Test Case Design', 'category' => 'testing'],

            // ============================================
            // 🔐 CYBERSECURITY
            // ============================================
            ['name' => 'Network Security', 'category' => 'cybersecurity'],
            ['name' => 'Vulnerability Assessment', 'category' => 'cybersecurity'],
            ['name' => 'Penetration Testing', 'category' => 'cybersecurity'],
            ['name' => 'Ethical Hacking', 'category' => 'cybersecurity'],
            ['name' => 'SIEM (Splunk, QRadar)', 'category' => 'cybersecurity'],
            ['name' => 'Firewall Management', 'category' => 'cybersecurity'],
            ['name' => 'SOC Operations', 'category' => 'cybersecurity'],
            ['name' => 'GDPR Compliance', 'category' => 'cybersecurity'],
            ['name' => 'ISO 27001', 'category' => 'cybersecurity'],
            ['name' => 'CISSP', 'category' => 'cybersecurity'],
            ['name' => 'CEH (Certified Ethical Hacker)', 'category' => 'cybersecurity'],
            ['name' => 'CompTIA Security+', 'category' => 'cybersecurity'],
            ['name' => 'Application Security', 'category' => 'cybersecurity'],
            ['name' => 'Cloud Security', 'category' => 'cybersecurity'],
            ['name' => 'Incident Response', 'category' => 'cybersecurity'],

            // ============================================
            // 📱 MOBILE DEVELOPMENT
            // ============================================
            ['name' => 'Android Development (Kotlin)', 'category' => 'mobile'],
            ['name' => 'Android Development (Java)', 'category' => 'mobile'],
            ['name' => 'iOS Development (Swift)', 'category' => 'mobile'],
            ['name' => 'React Native', 'category' => 'mobile'],
            ['name' => 'Flutter', 'category' => 'mobile'],
            ['name' => 'Ionic', 'category' => 'mobile'],
            ['name' => 'Mobile App Security', 'category' => 'mobile'],
            ['name' => 'App Store Optimization (ASO)', 'category' => 'mobile'],
            ['name' => 'Mobile UI/UX Design', 'category' => 'mobile'],
            ['name' => 'Firebase Mobile', 'category' => 'mobile'],

            // ============================================
            // 🎨 DESIGN & CREATIVE
            // ============================================
            ['name' => 'UI/UX Design', 'category' => 'design'],
            ['name' => 'Figma', 'category' => 'design'],
            ['name' => 'Adobe XD', 'category' => 'design'],
            ['name' => 'Sketch', 'category' => 'design'],
            ['name' => 'Adobe Photoshop', 'category' => 'design'],
            ['name' => 'Adobe Illustrator', 'category' => 'design'],
            ['name' => 'Adobe InDesign', 'category' => 'design'],
            ['name' => 'Adobe Premiere Pro', 'category' => 'design'],
            ['name' => 'Adobe After Effects', 'category' => 'design'],
            ['name' => 'Canva', 'category' => 'design'],
            ['name' => 'User Research', 'category' => 'design'],
            ['name' => 'Wireframing', 'category' => 'design'],
            ['name' => 'Prototyping', 'category' => 'design'],
            ['name' => 'Responsive Design', 'category' => 'design'],
            ['name' => 'Design Thinking', 'category' => 'design'],

            // ============================================
            // 📊 ACCOUNTING & FINANCE
            // ============================================
            ['name' => 'QuickBooks', 'category' => 'finance'],
            ['name' => 'Xero', 'category' => 'finance'],
            ['name' => 'SAP FICO', 'category' => 'finance'],
            ['name' => 'Oracle Financials', 'category' => 'finance'],
            ['name' => 'MS Excel (Advanced)', 'category' => 'finance'],
            ['name' => 'Financial Modeling', 'category' => 'finance'],
            ['name' => 'Taxation (Pakistan)', 'category' => 'finance'],
            ['name' => 'Auditing', 'category' => 'finance'],
            ['name' => 'Budgeting & Forecasting', 'category' => 'finance'],
            ['name' => 'Corporate Finance', 'category' => 'finance'],
            ['name' => 'Cost Accounting', 'category' => 'finance'],
            ['name' => 'Financial Reporting', 'category' => 'finance'],
            ['name' => 'Zoho Books', 'category' => 'finance'],
            ['name' => 'IFRS Knowledge', 'category' => 'finance'],
            ['name' => 'CMA / CA / ACCA', 'category' => 'finance'],

            // ============================================
            // 📈 DIGITAL MARKETING
            // ============================================
            ['name' => 'Search Engine Optimization (SEO)', 'category' => 'marketing'],
            ['name' => 'Google Ads (PPC)', 'category' => 'marketing'],
            ['name' => 'Social Media Marketing', 'category' => 'marketing'],
            ['name' => 'Content Marketing', 'category' => 'marketing'],
            ['name' => 'Email Marketing (Mailchimp)', 'category' => 'marketing'],
            ['name' => 'Google Analytics', 'category' => 'marketing'],
            ['name' => 'Facebook Ads', 'category' => 'marketing'],
            ['name' => 'LinkedIn Ads', 'category' => 'marketing'],
            ['name' => 'Instagram Marketing', 'category' => 'marketing'],
            ['name' => 'YouTube Marketing', 'category' => 'marketing'],
            ['name' => 'Conversion Rate Optimization (CRO)', 'category' => 'marketing'],
            ['name' => 'Affiliate Marketing', 'category' => 'marketing'],
            ['name' => 'Influencer Marketing', 'category' => 'marketing'],
            ['name' => 'CRM Marketing', 'category' => 'marketing'],
            ['name' => 'Sales Funnel Optimization', 'category' => 'marketing'],

            // ============================================
            // 🏢 SALES & BUSINESS DEVELOPMENT
            // ============================================
            ['name' => 'B2B Sales', 'category' => 'sales'],
            ['name' => 'B2C Sales', 'category' => 'sales'],
            ['name' => 'Key Account Management', 'category' => 'sales'],
            ['name' => 'Salesforce CRM', 'category' => 'sales'],
            ['name' => 'HubSpot', 'category' => 'sales'],
            ['name' => 'Microsoft Dynamics', 'category' => 'sales'],
            ['name' => 'Zoho CRM', 'category' => 'sales'],
            ['name' => 'Negotiation Skills', 'category' => 'sales'],
            ['name' => 'Cold Calling', 'category' => 'sales'],
            ['name' => 'Lead Generation', 'category' => 'sales'],
            ['name' => 'Sales Strategy', 'category' => 'sales'],
            ['name' => 'Pitch Deck Creation', 'category' => 'sales'],
            ['name' => 'Market Research', 'category' => 'sales'],
            ['name' => 'Customer Relationship Management', 'category' => 'sales'],
            ['name' => 'Sales Forecasting', 'category' => 'sales'],

            // ============================================
            // 🤝 SOFT SKILLS
            // ============================================
            ['name' => 'Communication (Written & Verbal)', 'category' => 'soft_skills'],
            ['name' => 'Public Speaking', 'category' => 'soft_skills'],
            ['name' => 'Business English', 'category' => 'soft_skills'],
            ['name' => 'Problem Solving', 'category' => 'soft_skills'],
            ['name' => 'Critical Thinking', 'category' => 'soft_skills'],
            ['name' => 'Teamwork & Collaboration', 'category' => 'soft_skills'],
            ['name' => 'Leadership', 'category' => 'soft_skills'],
            ['name' => 'Mentoring & Coaching', 'category' => 'soft_skills'],
            ['name' => 'Time Management', 'category' => 'soft_skills'],
            ['name' => 'Project Management', 'category' => 'soft_skills'],
            ['name' => 'Agile / Scrum', 'category' => 'soft_skills'],
            ['name' => 'Kanban', 'category' => 'soft_skills'],
            ['name' => 'Jira', 'category' => 'soft_skills'],
            ['name' => 'Trello', 'category' => 'soft_skills'],
            ['name' => 'Adaptability & Flexibility', 'category' => 'soft_skills'],
            ['name' => 'Emotional Intelligence', 'category' => 'soft_skills'],
            ['name' => 'Conflict Resolution', 'category' => 'soft_skills'],
            ['name' => 'Stakeholder Management', 'category' => 'soft_skills'],
            ['name' => 'Decision Making', 'category' => 'soft_skills'],
            ['name' => 'Creativity & Innovation', 'category' => 'soft_skills'],
            ['name' => 'Attention to Detail', 'category' => 'soft_skills'],
            ['name' => 'Multitasking', 'category' => 'soft_skills'],
            ['name' => 'Work Ethic', 'category' => 'soft_skills'],
            ['name' => 'Professionalism', 'category' => 'soft_skills'],
            ['name' => 'Remote Work Collaboration', 'category' => 'soft_skills'],

            // ============================================
            // 📋 PROJECT MANAGEMENT & METHODOLOGIES
            // ============================================
            ['name' => 'PMP (Project Management Professional)', 'category' => 'project_management'],
            ['name' => 'PRINCE2', 'category' => 'project_management'],
            ['name' => 'Agile Methodologies', 'category' => 'project_management'],
            ['name' => 'Scrum Master', 'category' => 'project_management'],
            ['name' => 'SAFe (Scaled Agile)', 'category' => 'project_management'],
            ['name' => 'Lean Six Sigma', 'category' => 'project_management'],
            ['name' => 'Risk Management', 'category' => 'project_management'],
            ['name' => 'Budget Management', 'category' => 'project_management'],
            ['name' => 'Resource Allocation', 'category' => 'project_management'],
            ['name' => 'Project Planning', 'category' => 'project_management'],

            // ============================================
            // 🏢 HUMAN RESOURCES
            // ============================================
            ['name' => 'Recruitment & Talent Acquisition', 'category' => 'hr'],
            ['name' => 'Onboarding & Offboarding', 'category' => 'hr'],
            ['name' => 'Performance Management', 'category' => 'hr'],
            ['name' => 'Employee Engagement', 'category' => 'hr'],
            ['name' => 'HRIS (Human Resource Information Systems)', 'category' => 'hr'],
            ['name' => 'Payroll Management', 'category' => 'hr'],
            ['name' => 'Training & Development', 'category' => 'hr'],
            ['name' => 'Conflict Resolution', 'category' => 'hr'],  // ⚠️ DUPLICATE - will be skipped
            ['name' => 'Labor Laws (Pakistan)', 'category' => 'hr'],
            ['name' => 'Compensation & Benefits', 'category' => 'hr'],
            ['name' => 'HR Analytics', 'category' => 'hr'],
            ['name' => 'Diversity & Inclusion', 'category' => 'hr'],

            // ============================================
            // 🏥 HEALTHCARE & MEDICAL
            // ============================================
            ['name' => 'Patient Care', 'category' => 'healthcare'],
            ['name' => 'Medical Billing & Coding', 'category' => 'healthcare'],
            ['name' => 'EMR (Electronic Medical Records)', 'category' => 'healthcare'],
            ['name' => 'Health Informatics', 'category' => 'healthcare'],
            ['name' => 'Clinical Research', 'category' => 'healthcare'],
            ['name' => 'Medical Compliance', 'category' => 'healthcare'],
            ['name' => 'Pharmacovigilance', 'category' => 'healthcare'],
            ['name' => 'Telemedicine Platforms', 'category' => 'healthcare'],
            ['name' => 'ICU / ER Experience', 'category' => 'healthcare'],
            ['name' => 'Healthcare Administration', 'category' => 'healthcare'],

            // ============================================
            // 🏭 INDUSTRY-SPECIFIC SKILLS
            // ============================================
            ['name' => 'Oil & Gas Industry Knowledge', 'category' => 'industry_specific'],
            ['name' => 'Manufacturing Processes', 'category' => 'industry_specific'],
            ['name' => 'Supply Chain Management', 'category' => 'industry_specific'],
            ['name' => 'Logistics & Warehouse Management', 'category' => 'industry_specific'],
            ['name' => 'Procurement', 'category' => 'industry_specific'],
            ['name' => 'Export & Import Documentation', 'category' => 'industry_specific'],
            ['name' => 'Textile Industry Knowledge', 'category' => 'industry_specific'],
            ['name' => 'Construction Management', 'category' => 'industry_specific'],
            ['name' => 'Real Estate Management', 'category' => 'industry_specific'],
            ['name' => 'Agriculture & Farming', 'category' => 'industry_specific'],
            ['name' => 'Banking & Financial Services', 'category' => 'industry_specific'],
            ['name' => 'Insurance (General / Health / Life)', 'category' => 'industry_specific'],
            ['name' => 'Telecom Industry Knowledge', 'category' => 'industry_specific'],
            ['name' => 'Education & Training', 'category' => 'industry_specific'],
            ['name' => 'Hospitality & Tourism', 'category' => 'industry_specific'],

            // ============================================
            // 🔧 BPO / CUSTOMER SUPPORT
            // ============================================
            ['name' => 'Customer Support (Voice)', 'category' => 'bpo'],
            ['name' => 'Customer Support (Chat)', 'category' => 'bpo'],
            ['name' => 'Customer Support (Email)', 'category' => 'bpo'],
            ['name' => 'Technical Support', 'category' => 'bpo'],
            ['name' => 'Call Center Operations', 'category' => 'bpo'],
            ['name' => 'Complaint Handling', 'category' => 'bpo'],
            ['name' => 'Client Retention', 'category' => 'bpo'],
            ['name' => 'SLA Management', 'category' => 'bpo'],
            ['name' => 'Help Desk Support', 'category' => 'bpo'],
            ['name' => 'CRM Tools (Zendesk / Freshdesk)', 'category' => 'bpo'],

            // ============================================
            // 🛠️ OTHER TOOLS & TECHNOLOGIES
            // ============================================
            ['name' => 'Version Control (Git)', 'category' => 'tools'],
            ['name' => 'GitHub / GitLab', 'category' => 'tools'],
            ['name' => 'Bitbucket', 'category' => 'tools'],
            ['name' => 'NPM', 'category' => 'tools'],
            ['name' => 'Yarn', 'category' => 'tools'],
            ['name' => 'Composer', 'category' => 'tools'],
            ['name' => 'Apache / Nginx', 'category' => 'tools'],
            ['name' => 'cPanel / Plesk', 'category' => 'tools'],
            ['name' => 'DNS Management', 'category' => 'tools'],
            ['name' => 'SSL Configuration', 'category' => 'tools'],
        ];
    }
}
