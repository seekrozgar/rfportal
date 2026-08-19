<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        // ✅ Clear existing translations
        Translation::truncate();

        $translations = [
            // ============================================================
            // 🏠 MAIN MENU
            // ============================================================
            ['key' => 'Main', 'language_code' => 'ur', 'value' => 'مین'],
            ['key' => 'Main', 'language_code' => 'ar', 'value' => 'الرئيسية'],

            ['key' => 'Dashboard', 'language_code' => 'ur', 'value' => 'ڈیش بورڈ'],
            ['key' => 'Dashboard', 'language_code' => 'ar', 'value' => 'لوحة التحكم'],

            ['key' => 'Admin Users', 'language_code' => 'ur', 'value' => 'ایڈمن صارفین'],
            ['key' => 'Admin Users', 'language_code' => 'ar', 'value' => 'مستخدمي الإدارة'],

            // ============================================================
            // 📦 JOB MANAGEMENT
            // ============================================================
            ['key' => 'Job Management', 'language_code' => 'ur', 'value' => 'نوکری کا انتظام'],
            ['key' => 'Job Management', 'language_code' => 'ar' , 'value' => 'إدارة الوظائف'],

            ['key' => 'General Jobs', 'language_code' => 'ur', 'value' => 'جنرل نوکریاں'],
            ['key' => 'General Jobs', 'language_code' => 'ar', 'value' => 'وظائف عامة'],

            ['key' => 'Company Jobs', 'language_code' => 'ur', 'value' => 'کمپنی نوکریاں'],
            ['key' => 'Company Jobs', 'language_code' => 'ar', 'value' => 'وظائف الشركات'],

            // ============================================================
            // 📝 EDUCATION
            // ============================================================
            ['key' => 'Education', 'language_code' => 'ur', 'value' => 'تعلیم'],
            ['key' => 'Education', 'language_code' => 'ar', 'value' => 'التعليم'],

            ['key' => 'Scholarships', 'language_code' => 'ur', 'value' => 'اسکالرشپ'],
            ['key' => 'Scholarships', 'language_code' => 'ar', 'value' => 'منح دراسية'],

            ['key' => 'Admissions', 'language_code' => 'ur', 'value' => 'داخلے'],
            ['key' => 'Admissions', 'language_code' => 'ar', 'value' => 'القبول'],

            ['key' => 'Results', 'language_code' => 'ur', 'value' => 'نتائج'],
            ['key' => 'Results', 'language_code' => 'ar', 'value' => 'النتائج'],

            ['key' => 'News / Announcements', 'language_code' => 'ur', 'value' => 'خبریں / اعلانات'],
            ['key' => 'News / Announcements', 'language_code' => 'ar', 'value' => 'أخبار / إعلانات'],

            // ============================================================
            // 👥 USERS
            // ============================================================
            ['key' => 'Users', 'language_code' => 'ur', 'value' => 'صارفین'],
            ['key' => 'Users', 'language_code' => 'ar', 'value' => 'المستخدمين'],

            ['key' => 'User Profiles', 'language_code' => 'ur', 'value' => 'صارف پروفائلز'],
            ['key' => 'User Profiles', 'language_code' => 'ar', 'value' => 'ملفات تعريف المستخدمين'],

            // ============================================================
            // 📝 CONTENT
            // ============================================================
            ['key' => 'Content', 'language_code' => 'ur', 'value' => 'مواد'],
            ['key' => 'Content', 'language_code' => 'ar', 'value' => 'المحتوى'],

            ['key' => 'SEO', 'language_code' => 'ur', 'value' => 'ایس ای او'],
            ['key' => 'SEO', 'language_code' => 'ar', 'value' => 'تحسين محركات البحث'],

            ['key' => 'FAQs', 'language_code' => 'ur', 'value' => 'اکثر پوچھے گئے سوالات'],
            ['key' => 'FAQs', 'language_code' => 'ar', 'value' => 'الأسئلة الشائعة'],

            // ============================================================
            // 🌍 TRANSLATION
            // ============================================================
            ['key' => 'Translation', 'language_code' => 'ur', 'value' => 'ترجمہ'],
            ['key' => 'Translation', 'language_code' => 'ar', 'value' => 'الترجمة'],

            ['key' => 'Languages', 'language_code' => 'ur', 'value' => 'زبانیں'],
            ['key' => 'Languages', 'language_code' => 'ar', 'value' => 'اللغات'],

            // ============================================================
            // 🌍 LOCATION
            // ============================================================
            ['key' => 'Location', 'language_code' => 'ur', 'value' => 'مقام'],
            ['key' => 'Location', 'language_code' => 'ar', 'value' => 'الموقع'],

            ['key' => 'Countries', 'language_code' => 'ur', 'value' => 'ممالک'],
            ['key' => 'Countries', 'language_code' => 'ar', 'value' => 'الدول'],

            ['key' => 'States', 'language_code' => 'ur', 'value' => 'ریاستیں'],
            ['key' => 'States', 'language_code' => 'ar', 'value' => 'الولايات'],

            ['key' => 'Cities', 'language_code' => 'ur', 'value' => 'شہر'],
            ['key' => 'Cities', 'language_code' => 'ar', 'value' => 'المدن'],

            // ============================================================
            // 💰 PACKAGES & PAYMENTS
            // ============================================================
            ['key' => 'Packages', 'language_code' => 'ur', 'value' => 'پیکجز'],
            ['key' => 'Packages', 'language_code' => 'ar', 'value' => 'الباقات'],

            ['key' => 'Company Payments', 'language_code' => 'ur', 'value' => 'کمپنی ادائیگیاں'],
            ['key' => 'Company Payments', 'language_code' => 'ar', 'value' => 'مدفوعات الشركات'],

            ['key' => 'Seeker Payments', 'language_code' => 'ur', 'value' => 'تلاش کنندہ ادائیگیاں'],
            ['key' => 'Seeker Payments', 'language_code' => 'ar', 'value' => 'مدفوعات الباحثين'],

            // ============================================================
            // 🏷️ JOB ATTRIBUTES
            // ============================================================
            ['key' => 'Job Attributes', 'language_code' => 'ur', 'value' => 'نوکری کی خصوصیات'],
            ['key' => 'Job Attributes', 'language_code' => 'ar', 'value' => 'سمات الوظيفة'],

            ['key' => 'Language Levels', 'language_code' => 'ur', 'value' => 'زبان کی سطحیں'],
            ['key' => 'Language Levels', 'language_code' => 'ar', 'value' => 'مستويات اللغة'],

            ['key' => 'Career Levels', 'language_code' => 'ur', 'value' => 'کیریئر کی سطحیں'],
            ['key' => 'Career Levels', 'language_code' => 'ar', 'value' => 'مستويات المهنة'],

            ['key' => 'Functional Areas', 'language_code' => 'ur', 'value' => 'فنکشنل ایریاز'],
            ['key' => 'Functional Areas', 'language_code' => 'ar', 'value' => 'المجالات الوظيفية'],

            ['key' => 'Genders', 'language_code' => 'ur', 'value' => 'صنفیں'],
            ['key' => 'Genders', 'language_code' => 'ar', 'value' => 'الجنسين'],

            ['key' => 'Industries', 'language_code' => 'ur', 'value' => 'صنعتیں'],
            ['key' => 'Industries', 'language_code' => 'ar', 'value' => 'الصناعات'],

            ['key' => 'Job Experience', 'language_code' => 'ur', 'value' => 'نوکری کا تجربہ'],
            ['key' => 'Job Experience', 'language_code' => 'ar', 'value' => 'خبرة العمل'],

            ['key' => 'Job Skills', 'language_code' => 'ur', 'value' => 'نوکری کی مہارتیں'],
            ['key' => 'Job Skills', 'language_code' => 'ar', 'value' => 'مهارات العمل'],

            ['key' => 'Job Types', 'language_code' => 'ur', 'value' => 'نوکری کی اقسام'],
            ['key' => 'Job Types', 'language_code' => 'ar', 'value' => 'أنواع الوظائف'],

            ['key' => 'Job Shifts', 'language_code' => 'ur', 'value' => 'نوکری کی شفٹیں'],
            ['key' => 'Job Shifts', 'language_code' => 'ar', 'value' => 'نوبات العمل'],

            ['key' => 'Degree Levels', 'language_code' => 'ur', 'value' => 'ڈگری کی سطحیں'],
            ['key' => 'Degree Levels', 'language_code' => 'ar', 'value' => 'مستويات الدرجة'],

            ['key' => 'Degree Types', 'language_code' => 'ur', 'value' => 'ڈگری کی اقسام'],
            ['key' => 'Degree Types', 'language_code' => 'ar', 'value' => 'أنواع الدرجات'],

            ['key' => 'Major Subjects', 'language_code' => 'ur', 'value' => 'اہم مضامین'],
            ['key' => 'Major Subjects', 'language_code' => 'ar', 'value' => 'المواد الرئيسية'],

            ['key' => 'Result Types', 'language_code' => 'ur', 'value' => 'نتیجہ کی اقسام'],
            ['key' => 'Result Types', 'language_code' => 'ar', 'value' => 'أنواع النتائج'],

            ['key' => 'Marital Status', 'language_code' => 'ur', 'value' => 'ازدواجی حیثیت'],
            ['key' => 'Marital Status', 'language_code' => 'ar', 'value' => 'الحالة الاجتماعية'],

            ['key' => 'Ownership Types', 'language_code' => 'ur', 'value' => 'ملکیت کی اقسام'],
            ['key' => 'Ownership Types', 'language_code' => 'ar', 'value' => 'أنواع الملكية'],

            ['key' => 'Salary Periods', 'language_code' => 'ur', 'value' => 'تنخواہ کے ادوار'],
            ['key' => 'Salary Periods', 'language_code' => 'ar', 'value' => 'فترات الراتب'],

            // ============================================================
            // ⚙️ SYSTEM
            // ============================================================
            ['key' => 'System', 'language_code' => 'ur', 'value' => 'سسٹم'],
            ['key' => 'System', 'language_code' => 'ar', 'value' => 'النظام'],

            ['key' => 'Site Settings', 'language_code' => 'ur', 'value' => 'سائٹ کی ترتیبات'],
            ['key' => 'Site Settings', 'language_code' => 'ar', 'value' => 'إعدادات الموقع'],

            ['key' => 'My Profile', 'language_code' => 'ur', 'value' => 'میرا پروفائل'],
            ['key' => 'My Profile', 'language_code' => 'ar', 'value' => 'ملفي الشخصي'],

            ['key' => 'Change Password', 'language_code' => 'ur', 'value' => 'پاس ورڈ تبدیل کریں'],
            ['key' => 'Change Password', 'language_code' => 'ar', 'value' => 'تغيير كلمة المرور'],

            ['key' => 'Notifications', 'language_code' => 'ur', 'value' => 'اطلاعات'],
            ['key' => 'Notifications', 'language_code' => 'ar', 'value' => 'الإشعارات'],

            // ============================================================
            // 📊 DASHBOARD
            // ============================================================
            ['key' => 'Overview of your portal', 'language_code' => 'ur', 'value' => 'اپنے پورٹل کا جائزہ'],
            ['key' => 'Overview of your portal', 'language_code' => 'ar', 'value' => 'نظرة عامة على بوابتك'],

            ['key' => 'Total Jobs', 'language_code' => 'ur', 'value' => 'کل نوکریاں'],
            ['key' => 'Total Jobs', 'language_code' => 'ar', 'value' => 'إجمالي الوظائف'],

            ['key' => 'Total Companies', 'language_code' => 'ur', 'value' => 'کل کمپنیاں'],
            ['key' => 'Total Companies', 'language_code' => 'ar', 'value' => 'إجمالي الشركات'],

            ['key' => 'Total Users', 'language_code' => 'ur', 'value' => 'کل صارفین'],
            ['key' => 'Total Users', 'language_code' => 'ar' , 'value' => 'إجمالي المستخدمين'],

            ['key' => 'Revenue', 'language_code' => 'ur', 'value' => 'محصول'],
            ['key' => 'Revenue', 'language_code' => 'ar', 'value' => 'الإيرادات'],

            ['key' => 'Registered partners', 'language_code' => 'ur', 'value' => 'رجسٹرڈ پارٹنرز'],
            ['key' => 'Registered partners', 'language_code' => 'ar', 'value' => 'شركاء مسجلين'],

            ['key' => 'Total earnings', 'language_code' => 'ur', 'value' => 'کل کمائی'],
            ['key' => 'Total earnings', 'language_code' => 'ar', 'value' => 'إجمالي الأرباح'],

            ['key' => 'employers', 'language_code' => 'ur', 'value' => 'آجروں'],
            ['key' => 'employers', 'language_code' => 'ar', 'value' => 'أصحاب العمل'],

            ['key' => 'seekers', 'language_code' => 'ur', 'value' => 'طلباء'],
            ['key' => 'seekers', 'language_code' => 'ar', 'value' => 'باحثين'],

            // ============================================================
            // 📋 TABLE HEADERS
            // ============================================================
            ['key' => 'Recent Jobs', 'language_code' => 'ur', 'value' => 'حالیہ نوکریاں'],
            ['key' => 'Recent Jobs', 'language_code' => 'ar', 'value' => 'الوظائف الأخيرة'],

            ['key' => 'Recent Users', 'language_code' => 'ur', 'value' => 'حالیہ صارفین'],
            ['key' => 'Recent Users', 'language_code' => 'ar', 'value' => 'المستخدمين الأخيرين'],

            ['key' => 'Add New', 'language_code' => 'ur', 'value' => 'نیا شامل کریں'],
            ['key' => 'Add New', 'language_code' => 'ar', 'value' => 'إضافة جديد'],

            ['key' => 'View All', 'language_code' => 'ur', 'value' => 'سب دیکھیں'],
            ['key' => 'View All', 'language_code' => 'ar', 'value' => 'عرض الكل'],

            ['key' => 'Title', 'language_code' => 'ur', 'value' => 'عنوان'],
            ['key' => 'Title', 'language_code' => 'ar', 'value' => 'العنوان'],

            ['key' => 'Name', 'language_code' => 'ur', 'value' => 'نام'],
            ['key' => 'Name', 'language_code' => 'ar', 'value' => 'الاسم'],

            ['key' => 'Email', 'language_code' => 'ur', 'value' => 'ای میل'],
            ['key' => 'Email', 'language_code' => 'ar', 'value' => 'البريد الإلكتروني'],

            ['key' => 'Role', 'language_code' => 'ur', 'value' => 'کردار'],
            ['key' => 'Role', 'language_code' => 'ar', 'value' => 'الدور'],

            ['key' => 'Company', 'language_code' => 'ur', 'value' => 'کمپنی'],
            ['key' => 'Company', 'language_code' => 'ar', 'value' => 'الشركة'],

            ['key' => 'Location', 'language_code' => 'ur', 'value' => 'مقام'],
            ['key' => 'Location', 'language_code' => 'ar', 'value' => 'الموقع'],

            ['key' => 'Status', 'language_code' => 'ur', 'value' => 'حالت'],
            ['key' => 'Status', 'language_code' => 'ar', 'value' => 'الحالة'],

            ['key' => 'Actions', 'language_code' => 'ur', 'value' => 'اعمال'],
            ['key' => 'Actions', 'language_code' => 'ar', 'value' => 'الإجراءات'],

            ['key' => 'Active', 'language_code' => 'ur', 'value' => 'فعال'],
            ['key' => 'Active', 'language_code' => 'ar', 'value' => 'نشط'],

            ['key' => 'Inactive', 'language_code' => 'ur', 'value' => 'غیر فعال'],
            ['key' => 'Inactive', 'language_code' => 'ar', 'value' => 'غير نشط'],

            ['key' => 'No jobs found.', 'language_code' => 'ur', 'value' => 'کوئی نوکری نہیں ملی۔'],
            ['key' => 'No jobs found.', 'language_code' => 'ar', 'value' => 'لم يتم العثور على وظائف.'],

            ['key' => 'No users found.', 'language_code' => 'ur', 'value' => 'کوئی صارف نہیں ملا۔'],
            ['key' => 'No users found.', 'language_code' => 'ar', 'value' => 'لم يتم العثور على مستخدمين.'],

            // ============================================================
            // 🔘 BUTTONS & ACTIONS
            // ============================================================
            ['key' => 'Save', 'language_code' => 'ur', 'value' => 'محفوظ کریں'],
            ['key' => 'Save', 'language_code' => 'ar', 'value' => 'حفظ'],

            ['key' => 'Update', 'language_code' => 'ur', 'value' => 'اپ ڈیٹ کریں'],
            ['key' => 'Update', 'language_code' => 'ar', 'value' => 'تحديث'],

            ['key' => 'Cancel', 'language_code' => 'ur', 'value' => 'منسوخ کریں'],
            ['key' => 'Cancel', 'language_code' => 'ar', 'value' => 'إلغاء'],

            ['key' => 'Delete', 'language_code' => 'ur', 'value' => 'حذف کریں'],
            ['key' => 'Delete', 'language_code' => 'ar', 'value' => 'حذف'],

            ['key' => 'Edit', 'language_code' => 'ur', 'value' => 'ترمیم کریں'],
            ['key' => 'Edit', 'language_code' => 'ar', 'value' => 'تعديل'],

            ['key' => 'View', 'language_code' => 'ur', 'value' => 'دیکھیں'],
            ['key' => 'View', 'language_code' => 'ar', 'value' => 'عرض'],

            ['key' => 'Search', 'language_code' => 'ur', 'value' => 'تلاش کریں'],
            ['key' => 'Search', 'language_code' => 'ar', 'value' => 'بحث'],

            ['key' => 'Filter', 'language_code' => 'ur', 'value' => 'فلٹر کریں'],
            ['key' => 'Filter', 'language_code' => 'ar', 'value' => 'تصفية'],

            ['key' => 'Login', 'language_code' => 'ur', 'value' => 'لاگ ان'],
            ['key' => 'Login', 'language_code' => 'ar', 'value' => 'تسجيل الدخول'],

            ['key' => 'Logout', 'language_code' => 'ur', 'value' => 'لاگ آؤٹ'],
            ['key' => 'Logout', 'language_code' => 'ar', 'value' => 'تسجيل الخروج'],

            ['key' => 'Register', 'language_code' => 'ur', 'value' => 'رجسٹر کریں'],
            ['key' => 'Register', 'language_code' => 'ar', 'value' => 'تسجيل'],

            ['key' => 'Profile', 'language_code' => 'ur', 'value' => 'پروفائل'],
            ['key' => 'Profile', 'language_code' => 'ar', 'value' => 'الملف الشخصي'],

            ['key' => 'Settings', 'language_code' => 'ur', 'value' => 'ترتیبات'],
            ['key' => 'Settings', 'language_code' => 'ar', 'value' => 'الإعدادات'],

            ['key' => 'Admin Panel', 'language_code' => 'ur', 'value' => 'ایڈمن پینل'],
            ['key' => 'Admin Panel', 'language_code' => 'ar', 'value' => 'لوحة الإدارة'],
        ];

        foreach ($translations as $trans) {
            Translation::updateOrCreate(
                [
                    'key' => $trans['key'],
                    'language_code' => $trans['language_code'],
                    'group' => null,
                ],
                ['value' => $trans['value']]
            );
        }

        $this->command->info('✅ ' . count($translations) . ' translations seeded successfully!');
    }
}
