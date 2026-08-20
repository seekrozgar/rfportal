<?php
// database/seeders/WorldLocationsManualSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WorldLocationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌍 Seeding 18 Countries with States & Cities (Manual)...');

        // ============================================
        // STEP 1: TRUNCATE (Optional)
        // ============================================
        if ($this->command->confirm('⚠️ Truncate existing tables?', false)) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('cities')->truncate();
            DB::table('states')->truncate();
            DB::table('countries')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->command->info('✅ Tables truncated.');
        }

        // ============================================
        // STEP 2: COUNTRIES
        // ============================================
        $countries = $this->getCountries();
        $countryMap = [];

        foreach ($countries as $code => $name) {
            $id = DB::table('countries')->insertGetId([
                'name' => $name,
                'code' => $code,
                'phone_code' => $this->getPhoneCode($code),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $countryMap[$code] = $id;
        }
        $this->command->info('✅ ' . count($countryMap) . ' countries imported.');

        // ============================================
        // STEP 3: STATES
        // ============================================
        $states = $this->getStates();
        $stateMap = [];

        foreach ($states as $state) {
            $countryId = $countryMap[$state['country_code']] ?? null;
            if (!$countryId) continue;

            $id = DB::table('states')->insertGetId([
                'country_id' => $countryId,
                'name' => $state['name'],
                'code' => $state['code'] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $stateMap[$state['country_code'] . '|' . $state['name']] = $id;
        }
        $this->command->info('✅ ' . count($stateMap) . ' states imported.');

        // ============================================
        // STEP 4: CITIES
        // ============================================
        $cities = $this->getCities();
        $cityCount = 0;

        foreach ($cities as $city) {
            $key = $city['country_code'] . '|' . $city['state_name'];
            $stateId = $stateMap[$key] ?? null;
            if (!$stateId) continue;

            DB::table('cities')->insert([
                'state_id' => $stateId,
                'name' => $city['name'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $cityCount++;
        }

        $this->command->info('✅ ' . $cityCount . ' cities imported.');
        $this->command->info('🎉 Import Complete!');
    }

    // ============================================
    // COUNTRIES DATA
    // ============================================
    private function getCountries(): array
    {
        return [
            'PK' => 'Pakistan',
            'IN' => 'India',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'DE' => 'Germany',
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'NL' => 'Netherlands',
            'SG' => 'Singapore',
            'KR' => 'South Korea',
            'MY' => 'Malaysia',
            'IE' => 'Ireland',
            'QA' => 'Qatar',
            'KW' => 'Kuwait',
            'NZ' => 'New Zealand',
            'CH' => 'Switzerland',
            'JP' => 'Japan',
        ];
    }

    private function getPhoneCode(string $code): ?string
    {
        $codes = [
            'PK' => '+92', 'IN' => '+91', 'US' => '+1', 'GB' => '+44',
            'DE' => '+49', 'AE' => '+971', 'SA' => '+966', 'CA' => '+1',
            'AU' => '+61', 'NL' => '+31', 'SG' => '+65', 'KR' => '+82',
            'MY' => '+60', 'IE' => '+353', 'QA' => '+974', 'KW' => '+965',
            'NZ' => '+64', 'CH' => '+41', 'JP' => '+81',
        ];
        return $codes[$code] ?? null;
    }

    // ============================================
    // STATES DATA
    // ============================================
    private function getStates(): array
    {
        return [
            // ==================== PAKISTAN ====================
            ['country_code' => 'PK', 'name' => 'Punjab', 'code' => 'PB'],
            ['country_code' => 'PK', 'name' => 'Sindh', 'code' => 'SD'],
            ['country_code' => 'PK', 'name' => 'Khyber Pakhtunkhwa', 'code' => 'KP'],
            ['country_code' => 'PK', 'name' => 'Balochistan', 'code' => 'BL'],
            ['country_code' => 'PK', 'name' => 'Islamabad Capital Territory', 'code' => 'IS'],
            ['country_code' => 'PK', 'name' => 'Azad Jammu & Kashmir', 'code' => 'AJK'],
            ['country_code' => 'PK', 'name' => 'Gilgit-Baltistan', 'code' => 'GB'],

            // ==================== INDIA ====================
            ['country_code' => 'IN', 'name' => 'Andhra Pradesh', 'code' => 'AP'],
            ['country_code' => 'IN', 'name' => 'Assam', 'code' => 'AS'],
            ['country_code' => 'IN', 'name' => 'Bihar', 'code' => 'BR'],
            ['country_code' => 'IN', 'name' => 'Chhattisgarh', 'code' => 'CT'],
            ['country_code' => 'IN', 'name' => 'Delhi', 'code' => 'DL'],
            ['country_code' => 'IN', 'name' => 'Gujarat', 'code' => 'GJ'],
            ['country_code' => 'IN', 'name' => 'Haryana', 'code' => 'HR'],
            ['country_code' => 'IN', 'name' => 'Jharkhand', 'code' => 'JH'],
            ['country_code' => 'IN', 'name' => 'Karnataka', 'code' => 'KA'],
            ['country_code' => 'IN', 'name' => 'Kerala', 'code' => 'KL'],
            ['country_code' => 'IN', 'name' => 'Madhya Pradesh', 'code' => 'MP'],
            ['country_code' => 'IN', 'name' => 'Maharashtra', 'code' => 'MH'],
            ['country_code' => 'IN', 'name' => 'Odisha', 'code' => 'OR'],
            ['country_code' => 'IN', 'name' => 'Punjab', 'code' => 'PB'],
            ['country_code' => 'IN', 'name' => 'Rajasthan', 'code' => 'RJ'],
            ['country_code' => 'IN', 'name' => 'Tamil Nadu', 'code' => 'TN'],
            ['country_code' => 'IN', 'name' => 'Telangana', 'code' => 'TS'],
            ['country_code' => 'IN', 'name' => 'Uttar Pradesh', 'code' => 'UP'],
            ['country_code' => 'IN', 'name' => 'West Bengal', 'code' => 'WB'],

            // ==================== USA ====================
            ['country_code' => 'US', 'name' => 'Alabama', 'code' => 'AL'],
            ['country_code' => 'US', 'name' => 'Alaska', 'code' => 'AK'],
            ['country_code' => 'US', 'name' => 'Arizona', 'code' => 'AZ'],
            ['country_code' => 'US', 'name' => 'Arkansas', 'code' => 'AR'],
            ['country_code' => 'US', 'name' => 'California', 'code' => 'CA'],
            ['country_code' => 'US', 'name' => 'Colorado', 'code' => 'CO'],
            ['country_code' => 'US', 'name' => 'Connecticut', 'code' => 'CT'],
            ['country_code' => 'US', 'name' => 'Delaware', 'code' => 'DE'],
            ['country_code' => 'US', 'name' => 'Florida', 'code' => 'FL'],
            ['country_code' => 'US', 'name' => 'Georgia', 'code' => 'GA'],
            ['country_code' => 'US', 'name' => 'Hawaii', 'code' => 'HI'],
            ['country_code' => 'US', 'name' => 'Idaho', 'code' => 'ID'],
            ['country_code' => 'US', 'name' => 'Illinois', 'code' => 'IL'],
            ['country_code' => 'US', 'name' => 'Indiana', 'code' => 'IN'],
            ['country_code' => 'US', 'name' => 'Iowa', 'code' => 'IA'],
            ['country_code' => 'US', 'name' => 'Kansas', 'code' => 'KS'],
            ['country_code' => 'US', 'name' => 'Kentucky', 'code' => 'KY'],
            ['country_code' => 'US', 'name' => 'Louisiana', 'code' => 'LA'],
            ['country_code' => 'US', 'name' => 'Maine', 'code' => 'ME'],
            ['country_code' => 'US', 'name' => 'Maryland', 'code' => 'MD'],
            ['country_code' => 'US', 'name' => 'Massachusetts', 'code' => 'MA'],
            ['country_code' => 'US', 'name' => 'Michigan', 'code' => 'MI'],
            ['country_code' => 'US', 'name' => 'Minnesota', 'code' => 'MN'],
            ['country_code' => 'US', 'name' => 'Mississippi', 'code' => 'MS'],
            ['country_code' => 'US', 'name' => 'Missouri', 'code' => 'MO'],
            ['country_code' => 'US', 'name' => 'Montana', 'code' => 'MT'],
            ['country_code' => 'US', 'name' => 'Nebraska', 'code' => 'NE'],
            ['country_code' => 'US', 'name' => 'Nevada', 'code' => 'NV'],
            ['country_code' => 'US', 'name' => 'New Hampshire', 'code' => 'NH'],
            ['country_code' => 'US', 'name' => 'New Jersey', 'code' => 'NJ'],
            ['country_code' => 'US', 'name' => 'New Mexico', 'code' => 'NM'],
            ['country_code' => 'US', 'name' => 'New York', 'code' => 'NY'],
            ['country_code' => 'US', 'name' => 'North Carolina', 'code' => 'NC'],
            ['country_code' => 'US', 'name' => 'North Dakota', 'code' => 'ND'],
            ['country_code' => 'US', 'name' => 'Ohio', 'code' => 'OH'],
            ['country_code' => 'US', 'name' => 'Oklahoma', 'code' => 'OK'],
            ['country_code' => 'US', 'name' => 'Oregon', 'code' => 'OR'],
            ['country_code' => 'US', 'name' => 'Pennsylvania', 'code' => 'PA'],
            ['country_code' => 'US', 'name' => 'Rhode Island', 'code' => 'RI'],
            ['country_code' => 'US', 'name' => 'South Carolina', 'code' => 'SC'],
            ['country_code' => 'US', 'name' => 'South Dakota', 'code' => 'SD'],
            ['country_code' => 'US', 'name' => 'Tennessee', 'code' => 'TN'],
            ['country_code' => 'US', 'name' => 'Texas', 'code' => 'TX'],
            ['country_code' => 'US', 'name' => 'Utah', 'code' => 'UT'],
            ['country_code' => 'US', 'name' => 'Vermont', 'code' => 'VT'],
            ['country_code' => 'US', 'name' => 'Virginia', 'code' => 'VA'],
            ['country_code' => 'US', 'name' => 'Washington', 'code' => 'WA'],
            ['country_code' => 'US', 'name' => 'West Virginia', 'code' => 'WV'],
            ['country_code' => 'US', 'name' => 'Wisconsin', 'code' => 'WI'],
            ['country_code' => 'US', 'name' => 'Wyoming', 'code' => 'WY'],

            // ==================== UK ====================
            ['country_code' => 'GB', 'name' => 'England', 'code' => 'ENG'],
            ['country_code' => 'GB', 'name' => 'Scotland', 'code' => 'SCT'],
            ['country_code' => 'GB', 'name' => 'Wales', 'code' => 'WLS'],
            ['country_code' => 'GB', 'name' => 'Northern Ireland', 'code' => 'NIR'],

            // ==================== GERMANY ====================
            ['country_code' => 'DE', 'name' => 'Baden-Württemberg', 'code' => 'BW'],
            ['country_code' => 'DE', 'name' => 'Bavaria', 'code' => 'BY'],
            ['country_code' => 'DE', 'name' => 'Berlin', 'code' => 'BE'],
            ['country_code' => 'DE', 'name' => 'Brandenburg', 'code' => 'BB'],
            ['country_code' => 'DE', 'name' => 'Bremen', 'code' => 'HB'],
            ['country_code' => 'DE', 'name' => 'Hamburg', 'code' => 'HH'],
            ['country_code' => 'DE', 'name' => 'Hesse', 'code' => 'HE'],
            ['country_code' => 'DE', 'name' => 'Lower Saxony', 'code' => 'NI'],
            ['country_code' => 'DE', 'name' => 'Mecklenburg-Vorpommern', 'code' => 'MV'],
            ['country_code' => 'DE', 'name' => 'North Rhine-Westphalia', 'code' => 'NW'],
            ['country_code' => 'DE', 'name' => 'Rhineland-Palatinate', 'code' => 'RP'],
            ['country_code' => 'DE', 'name' => 'Saarland', 'code' => 'SL'],
            ['country_code' => 'DE', 'name' => 'Saxony', 'code' => 'SN'],
            ['country_code' => 'DE', 'name' => 'Saxony-Anhalt', 'code' => 'ST'],
            ['country_code' => 'DE', 'name' => 'Schleswig-Holstein', 'code' => 'SH'],
            ['country_code' => 'DE', 'name' => 'Thuringia', 'code' => 'TH'],

            // ==================== UAE ====================
            ['country_code' => 'AE', 'name' => 'Abu Dhabi', 'code' => 'AD'],
            ['country_code' => 'AE', 'name' => 'Ajman', 'code' => 'AJ'],
            ['country_code' => 'AE', 'name' => 'Dubai', 'code' => 'DB'],
            ['country_code' => 'AE', 'name' => 'Fujairah', 'code' => 'FU'],
            ['country_code' => 'AE', 'name' => 'Ras Al Khaimah', 'code' => 'RK'],
            ['country_code' => 'AE', 'name' => 'Sharjah', 'code' => 'SH'],
            ['country_code' => 'AE', 'name' => 'Umm Al-Quwain', 'code' => 'UQ'],

            // ==================== SAUDI ARABIA ====================
            ['country_code' => 'SA', 'name' => 'Asir', 'code' => 'AS'],
            ['country_code' => 'SA', 'name' => 'Eastern Province', 'code' => 'EP'],
            ['country_code' => 'SA', 'name' => 'Mecca', 'code' => 'MK'],
            ['country_code' => 'SA', 'name' => 'Medina', 'code' => 'MD'],
            ['country_code' => 'SA', 'name' => 'Riyadh', 'code' => 'RY'],
            ['country_code' => 'SA', 'name' => 'Tabuk', 'code' => 'TB'],
            ['country_code' => 'SA', 'name' => 'Qassim', 'code' => 'QS'],
            ['country_code' => 'SA', 'name' => 'Jizan', 'code' => 'JZ'],
            ['country_code' => 'SA', 'name' => 'Najran', 'code' => 'NR'],
            ['country_code' => 'SA', 'name' => 'Hail', 'code' => 'HL'],
            ['country_code' => 'SA', 'name' => 'Al Bahah', 'code' => 'BA'],
            ['country_code' => 'SA', 'name' => 'Al Jawf', 'code' => 'JF'],
            ['country_code' => 'SA', 'name' => 'Northern Borders', 'code' => 'NB'],

            // ==================== CANADA ====================
            ['country_code' => 'CA', 'name' => 'Alberta', 'code' => 'AB'],
            ['country_code' => 'CA', 'name' => 'British Columbia', 'code' => 'BC'],
            ['country_code' => 'CA', 'name' => 'Manitoba', 'code' => 'MB'],
            ['country_code' => 'CA', 'name' => 'New Brunswick', 'code' => 'NB'],
            ['country_code' => 'CA', 'name' => 'Newfoundland and Labrador', 'code' => 'NL'],
            ['country_code' => 'CA', 'name' => 'Nova Scotia', 'code' => 'NS'],
            ['country_code' => 'CA', 'name' => 'Ontario', 'code' => 'ON'],
            ['country_code' => 'CA', 'name' => 'Prince Edward Island', 'code' => 'PE'],
            ['country_code' => 'CA', 'name' => 'Quebec', 'code' => 'QC'],
            ['country_code' => 'CA', 'name' => 'Saskatchewan', 'code' => 'SK'],

            // ==================== AUSTRALIA ====================
            ['country_code' => 'AU', 'name' => 'New South Wales', 'code' => 'NSW'],
            ['country_code' => 'AU', 'name' => 'Victoria', 'code' => 'VIC'],
            ['country_code' => 'AU', 'name' => 'Queensland', 'code' => 'QLD'],
            ['country_code' => 'AU', 'name' => 'Western Australia', 'code' => 'WA'],
            ['country_code' => 'AU', 'name' => 'South Australia', 'code' => 'SA'],
            ['country_code' => 'AU', 'name' => 'Tasmania', 'code' => 'TAS'],

            // ==================== NETHERLANDS ====================
            ['country_code' => 'NL', 'name' => 'North Holland', 'code' => 'NH'],
            ['country_code' => 'NL', 'name' => 'South Holland', 'code' => 'SH'],
            ['country_code' => 'NL', 'name' => 'Utrecht', 'code' => 'UT'],
            ['country_code' => 'NL', 'name' => 'Gelderland', 'code' => 'GE'],
            ['country_code' => 'NL', 'name' => 'North Brabant', 'code' => 'NB'],
            ['country_code' => 'NL', 'name' => 'Limburg', 'code' => 'LI'],
            ['country_code' => 'NL', 'name' => 'Overijssel', 'code' => 'OV'],
            ['country_code' => 'NL', 'name' => 'Flevoland', 'code' => 'FL'],
            ['country_code' => 'NL', 'name' => 'Groningen', 'code' => 'GR'],
            ['country_code' => 'NL', 'name' => 'Friesland', 'code' => 'FR'],
            ['country_code' => 'NL', 'name' => 'Drenthe', 'code' => 'DR'],
            ['country_code' => 'NL', 'name' => 'Zeeland', 'code' => 'ZE'],

            // ==================== SINGAPORE ====================
            ['country_code' => 'SG', 'name' => 'Central Region', 'code' => 'CR'],
            ['country_code' => 'SG', 'name' => 'East Region', 'code' => 'ER'],
            ['country_code' => 'SG', 'name' => 'North Region', 'code' => 'NR'],
            ['country_code' => 'SG', 'name' => 'North-East Region', 'code' => 'NE'],
            ['country_code' => 'SG', 'name' => 'West Region', 'code' => 'WR'],

            // ==================== SOUTH KOREA ====================
            ['country_code' => 'KR', 'name' => 'Seoul', 'code' => 'SEL'],
            ['country_code' => 'KR', 'name' => 'Busan', 'code' => 'BUS'],
            ['country_code' => 'KR', 'name' => 'Incheon', 'code' => 'INC'],
            ['country_code' => 'KR', 'name' => 'Daegu', 'code' => 'DAE'],
            ['country_code' => 'KR', 'name' => 'Daejeon', 'code' => 'DAJ'],
            ['country_code' => 'KR', 'name' => 'Gwangju', 'code' => 'GWA'],
            ['country_code' => 'KR', 'name' => 'Gyeonggi', 'code' => 'GGI'],
            ['country_code' => 'KR', 'name' => 'Gangwon', 'code' => 'GAN'],

            // ==================== MALAYSIA ====================
            ['country_code' => 'MY', 'name' => 'Selangor', 'code' => 'SEL'],
            ['country_code' => 'MY', 'name' => 'Kuala Lumpur', 'code' => 'KL'],
            ['country_code' => 'MY', 'name' => 'Johor', 'code' => 'JHR'],
            ['country_code' => 'MY', 'name' => 'Penang', 'code' => 'PEN'],
            ['country_code' => 'MY', 'name' => 'Sarawak', 'code' => 'SAR'],
            ['country_code' => 'MY', 'name' => 'Sabah', 'code' => 'SAB'],
            ['country_code' => 'MY', 'name' => 'Pahang', 'code' => 'PAH'],
            ['country_code' => 'MY', 'name' => 'Perak', 'code' => 'PRK'],
            ['country_code' => 'MY', 'name' => 'Kedah', 'code' => 'KED'],
            ['country_code' => 'MY', 'name' => 'Terengganu', 'code' => 'TER'],
            ['country_code' => 'MY', 'name' => 'Kelantan', 'code' => 'KEL'],
            ['country_code' => 'MY', 'name' => 'Malacca', 'code' => 'MEL'],
            ['country_code' => 'MY', 'name' => 'Negeri Sembilan', 'code' => 'NGS'],

            // ==================== IRELAND ====================
            ['country_code' => 'IE', 'name' => 'Dublin', 'code' => 'DUB'],
            ['country_code' => 'IE', 'name' => 'Cork', 'code' => 'COR'],
            ['country_code' => 'IE', 'name' => 'Galway', 'code' => 'GAL'],
            ['country_code' => 'IE', 'name' => 'Limerick', 'code' => 'LIM'],
            ['country_code' => 'IE', 'name' => 'Waterford', 'code' => 'WAT'],
            ['country_code' => 'IE', 'name' => 'Meath', 'code' => 'MTH'],
            ['country_code' => 'IE', 'name' => 'Kildare', 'code' => 'KID'],
            ['country_code' => 'IE', 'name' => 'Wicklow', 'code' => 'WIC'],
            ['country_code' => 'IE', 'name' => 'Louth', 'code' => 'LOU'],
            ['country_code' => 'IE', 'name' => 'Tipperary', 'code' => 'TIP'],
            ['country_code' => 'IE', 'name' => 'Westmeath', 'code' => 'WES'],
            ['country_code' => 'IE', 'name' => 'Offaly', 'code' => 'OFF'],
            ['country_code' => 'IE', 'name' => 'Laois', 'code' => 'LAO'],
            ['country_code' => 'IE', 'name' => 'Longford', 'code' => 'LON'],
            ['country_code' => 'IE', 'name' => 'Cavan', 'code' => 'CAV'],
            ['country_code' => 'IE', 'name' => 'Monaghan', 'code' => 'MON'],
            ['country_code' => 'IE', 'name' => 'Leitrim', 'code' => 'LEI'],
            ['country_code' => 'IE', 'name' => 'Roscommon', 'code' => 'ROS'],
            ['country_code' => 'IE', 'name' => 'Sligo', 'code' => 'SLI'],
            ['country_code' => 'IE', 'name' => 'Mayo', 'code' => 'MAY'],
            ['country_code' => 'IE', 'name' => 'Donegal', 'code' => 'DON'],
            ['country_code' => 'IE', 'name' => 'Kerry', 'code' => 'KER'],
            ['country_code' => 'IE', 'name' => 'Clare', 'code' => 'CLA'],
            ['country_code' => 'IE', 'name' => 'Wexford', 'code' => 'WEX'],
            ['country_code' => 'IE', 'name' => 'Kilkenny', 'code' => 'KIL'],

            // ==================== QATAR ====================
            ['country_code' => 'QA', 'name' => 'Doha', 'code' => 'DO'],
            ['country_code' => 'QA', 'name' => 'Al Rayyan', 'code' => 'RY'],
            ['country_code' => 'QA', 'name' => 'Al Wakrah', 'code' => 'WK'],
            ['country_code' => 'QA', 'name' => 'Al Khor', 'code' => 'KH'],
            ['country_code' => 'QA', 'name' => 'Umm Salal', 'code' => 'US'],
            ['country_code' => 'QA', 'name' => 'Al Daayen', 'code' => 'DA'],
            ['country_code' => 'QA', 'name' => 'Al Shahaniya', 'code' => 'SH'],
            ['country_code' => 'QA', 'name' => 'Al Shamal', 'code' => 'SM'],

            // ==================== KUWAIT ====================
            ['country_code' => 'KW', 'name' => 'Capital', 'code' => 'KU'],
            ['country_code' => 'KW', 'name' => 'Hawalli', 'code' => 'HA'],
            ['country_code' => 'KW', 'name' => 'Ahmadi', 'code' => 'AH'],
            ['country_code' => 'KW', 'name' => 'Farwaniya', 'code' => 'FA'],
            ['country_code' => 'KW', 'name' => 'Jahra', 'code' => 'JA'],
            ['country_code' => 'KW', 'name' => 'Mubarak Al-Kabeer', 'code' => 'MU'],

            // ==================== NEW ZEALAND ====================
            ['country_code' => 'NZ', 'name' => 'Auckland', 'code' => 'AUK'],
            ['country_code' => 'NZ', 'name' => 'Wellington', 'code' => 'WEL'],
            ['country_code' => 'NZ', 'name' => 'Canterbury', 'code' => 'CAN'],
            ['country_code' => 'NZ', 'name' => 'Waikato', 'code' => 'WAI'],
            ['country_code' => 'NZ', 'name' => 'Bay of Plenty', 'code' => 'BOP'],
            ['country_code' => 'NZ', 'name' => 'Otago', 'code' => 'OTA'],
            ['country_code' => 'NZ', 'name' => 'Manawatu-Wanganui', 'code' => 'MWA'],
            ['country_code' => 'NZ', 'name' => 'Taranaki', 'code' => 'TAR'],
            ['country_code' => 'NZ', 'name' => 'Hawke\'s Bay', 'code' => 'HAW'],
            ['country_code' => 'NZ', 'name' => 'Southland', 'code' => 'SOU'],
            ['country_code' => 'NZ', 'name' => 'Nelson', 'code' => 'NEL'],
            ['country_code' => 'NZ', 'name' => 'Marlborough', 'code' => 'MAR'],
            ['country_code' => 'NZ', 'name' => 'West Coast', 'code' => 'WCO'],
            ['country_code' => 'NZ', 'name' => 'Gisborne', 'code' => 'GIS'],
            ['country_code' => 'NZ', 'name' => 'Northland', 'code' => 'NOR'],
            ['country_code' => 'NZ', 'name' => 'Tasman', 'code' => 'TAS'],

            // ==================== SWITZERLAND ====================
            ['country_code' => 'CH', 'name' => 'Zürich', 'code' => 'ZH'],
            ['country_code' => 'CH', 'name' => 'Bern', 'code' => 'BE'],
            ['country_code' => 'CH', 'name' => 'Geneva', 'code' => 'GE'],
            ['country_code' => 'CH', 'name' => 'Basel-Stadt', 'code' => 'BS'],
            ['country_code' => 'CH', 'name' => 'Lucerne', 'code' => 'LU'],
            ['country_code' => 'CH', 'name' => 'Lugano', 'code' => 'TI'],
            ['country_code' => 'CH', 'name' => 'Lausanne', 'code' => 'VD'],
            ['country_code' => 'CH', 'name' => 'St. Gallen', 'code' => 'SG'],
            ['country_code' => 'CH', 'name' => 'Aargau', 'code' => 'AG'],
            ['country_code' => 'CH', 'name' => 'Valais', 'code' => 'VS'],
            ['country_code' => 'CH', 'name' => 'Graubünden', 'code' => 'GR'],
            ['country_code' => 'CH', 'name' => 'Fribourg', 'code' => 'FR'],
            ['country_code' => 'CH', 'name' => 'Solothurn', 'code' => 'SO'],
            ['country_code' => 'CH', 'name' => 'Schwyz', 'code' => 'SZ'],
            ['country_code' => 'CH', 'name' => 'Zug', 'code' => 'ZG'],
            ['country_code' => 'CH', 'name' => 'Neuchâtel', 'code' => 'NE'],
            ['country_code' => 'CH', 'name' => 'Jura', 'code' => 'JU'],
            ['country_code' => 'CH', 'name' => 'Thurgau', 'code' => 'TG'],
            ['country_code' => 'CH', 'name' => 'Appenzell Ausserrhoden', 'code' => 'AR'],
            ['country_code' => 'CH', 'name' => 'Appenzell Innerrhoden', 'code' => 'AI'],
            ['country_code' => 'CH', 'name' => 'Basel-Landschaft', 'code' => 'BL'],
            ['country_code' => 'CH', 'name' => 'Glarus', 'code' => 'GL'],
            ['country_code' => 'CH', 'name' => 'Nidwalden', 'code' => 'NW'],
            ['country_code' => 'CH', 'name' => 'Obwalden', 'code' => 'OW'],
            ['country_code' => 'CH', 'name' => 'Schaffhausen', 'code' => 'SH'],
            ['country_code' => 'CH', 'name' => 'Uri', 'code' => 'UR'],

            // ==================== JAPAN ====================
            ['country_code' => 'JP', 'name' => 'Tokyo', 'code' => 'TYO'],
            ['country_code' => 'JP', 'name' => 'Osaka', 'code' => 'OSA'],
            ['country_code' => 'JP', 'name' => 'Kanagawa', 'code' => 'KNG'],
            ['country_code' => 'JP', 'name' => 'Aichi', 'code' => 'AIC'],
            ['country_code' => 'JP', 'name' => 'Saitama', 'code' => 'SAI'],
            ['country_code' => 'JP', 'name' => 'Chiba', 'code' => 'CHI'],
            ['country_code' => 'JP', 'name' => 'Hyogo', 'code' => 'HYO'],
            ['country_code' => 'JP', 'name' => 'Hokkaido', 'code' => 'HOK'],
            ['country_code' => 'JP', 'name' => 'Fukuoka', 'code' => 'FUK'],
            ['country_code' => 'JP', 'name' => 'Shizuoka', 'code' => 'SHI'],
            ['country_code' => 'JP', 'name' => 'Ibaraki', 'code' => 'IBA'],
            ['country_code' => 'JP', 'name' => 'Tochigi', 'code' => 'TOC'],
            ['country_code' => 'JP', 'name' => 'Gunma', 'code' => 'GUM'],
            ['country_code' => 'JP', 'name' => 'Niigata', 'code' => 'NII'],
            ['country_code' => 'JP', 'name' => 'Nagano', 'code' => 'NAG'],
            ['country_code' => 'JP', 'name' => 'Miyagi', 'code' => 'MIY'],
            ['country_code' => 'JP', 'name' => 'Hiroshima', 'code' => 'HIR'],
            ['country_code' => 'JP', 'name' => 'Kyoto', 'code' => 'KYO'],
            ['country_code' => 'JP', 'name' => 'Okayama', 'code' => 'OKA'],
            ['country_code' => 'JP', 'name' => 'Kumamoto', 'code' => 'KUM'],
        ];
    }

    // ============================================
    // CITIES DATA (Major Cities Only)
    // ============================================
    private function getCities(): array
    {
        return [
            // ==================== PAKISTAN ====================
            // Punjab
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Lahore'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Faisalabad'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Rawalpindi'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Gujranwala'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Multan'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Bahawalpur'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Sargodha'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Sialkot'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Sheikhupura'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Rahim Yar Khan'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Jhang'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Dera Ghazi Khan'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Gujrat'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Sahiwal'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Kasur'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Okara'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Wah Cantonment'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Chiniot'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Hafizabad'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Khanewal'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Muzaffargarh'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Pakpattan'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Lodhran'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Jhelum'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Vehari'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Layyah'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Mandi Bahauddin'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Attock'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Toba Tek Singh'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Mianwali'],
            ['country_code' => 'PK', 'state_name' => 'Punjab', 'name' => 'Bhakkar'],

            // Sindh
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Karachi'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Hyderabad'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Sukkur'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Larkana'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Mirpur Khas'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Nawabshah'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Shikarpur'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Jacobabad'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Khairpur'],
            ['country_code' => 'PK', 'state_name' => 'Sindh', 'name' => 'Badin'],

            // Khyber Pakhtunkhwa
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Peshawar'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Mardan'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Abbottabad'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Swabi'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Kohat'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Dera Ismail Khan'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Mansehra'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Nowshera'],
            ['country_code' => 'PK', 'state_name' => 'Khyber Pakhtunkhwa', 'name' => 'Charsadda'],

            // Balochistan
            ['country_code' => 'PK', 'state_name' => 'Balochistan', 'name' => 'Quetta'],
            ['country_code' => 'PK', 'state_name' => 'Balochistan', 'name' => 'Turbat'],
            ['country_code' => 'PK', 'state_name' => 'Balochistan', 'name' => 'Khuzdar'],
            ['country_code' => 'PK', 'state_name' => 'Balochistan', 'name' => 'Chaman'],
            ['country_code' => 'PK', 'state_name' => 'Balochistan', 'name' => 'Gwadar'],

            // Islamabad
            ['country_code' => 'PK', 'state_name' => 'Islamabad Capital Territory', 'name' => 'Islamabad'],

            // ==================== INDIA ====================
            ['country_code' => 'IN', 'state_name' => 'Maharashtra', 'name' => 'Mumbai'],
            ['country_code' => 'IN', 'state_name' => 'Delhi', 'name' => 'Delhi'],
            ['country_code' => 'IN', 'state_name' => 'Karnataka', 'name' => 'Bangalore'],
            ['country_code' => 'IN', 'state_name' => 'Telangana', 'name' => 'Hyderabad'],
            ['country_code' => 'IN', 'state_name' => 'Gujarat', 'name' => 'Ahmedabad'],
            ['country_code' => 'IN', 'state_name' => 'Tamil Nadu', 'name' => 'Chennai'],
            ['country_code' => 'IN', 'state_name' => 'West Bengal', 'name' => 'Kolkata'],
            ['country_code' => 'IN', 'state_name' => 'Uttar Pradesh', 'name' => 'Lucknow'],
            ['country_code' => 'IN', 'state_name' => 'Punjab', 'name' => 'Chandigarh'],

            // ==================== USA ====================
            ['country_code' => 'US', 'state_name' => 'New York', 'name' => 'New York City'],
            ['country_code' => 'US', 'state_name' => 'California', 'name' => 'Los Angeles'],
            ['country_code' => 'US', 'state_name' => 'Illinois', 'name' => 'Chicago'],
            ['country_code' => 'US', 'state_name' => 'Texas', 'name' => 'Houston'],
            ['country_code' => 'US', 'state_name' => 'Arizona', 'name' => 'Phoenix'],
            ['country_code' => 'US', 'state_name' => 'Pennsylvania', 'name' => 'Philadelphia'],
            ['country_code' => 'US', 'state_name' => 'Texas', 'name' => 'San Antonio'],
            ['country_code' => 'US', 'state_name' => 'California', 'name' => 'San Diego'],
            ['country_code' => 'US', 'state_name' => 'Texas', 'name' => 'Dallas'],
            ['country_code' => 'US', 'state_name' => 'California', 'name' => 'San Jose'],

            // ==================== UK ====================
            ['country_code' => 'GB', 'state_name' => 'England', 'name' => 'London'],
            ['country_code' => 'GB', 'state_name' => 'England', 'name' => 'Birmingham'],
            ['country_code' => 'GB', 'state_name' => 'England', 'name' => 'Manchester'],
            ['country_code' => 'GB', 'state_name' => 'England', 'name' => 'Liverpool'],
            ['country_code' => 'GB', 'state_name' => 'Scotland', 'name' => 'Edinburgh'],
            ['country_code' => 'GB', 'state_name' => 'Scotland', 'name' => 'Glasgow'],
            ['country_code' => 'GB', 'state_name' => 'Wales', 'name' => 'Cardiff'],
            ['country_code' => 'GB', 'state_name' => 'Northern Ireland', 'name' => 'Belfast'],

            // ==================== GERMANY ====================
            ['country_code' => 'DE', 'state_name' => 'Berlin', 'name' => 'Berlin'],
            ['country_code' => 'DE', 'state_name' => 'Hamburg', 'name' => 'Hamburg'],
            ['country_code' => 'DE', 'state_name' => 'Bavaria', 'name' => 'Munich'],
            ['country_code' => 'DE', 'state_name' => 'North Rhine-Westphalia', 'name' => 'Cologne'],
            ['country_code' => 'DE', 'state_name' => 'Hesse', 'name' => 'Frankfurt'],
            ['country_code' => 'DE', 'state_name' => 'Baden-Württemberg', 'name' => 'Stuttgart'],
            ['country_code' => 'DE', 'state_name' => 'North Rhine-Westphalia', 'name' => 'Düsseldorf'],
            ['country_code' => 'DE', 'state_name' => 'Saxony', 'name' => 'Dresden'],
            ['country_code' => 'DE', 'state_name' => 'Lower Saxony', 'name' => 'Hanover'],

            // ==================== UAE ====================
            ['country_code' => 'AE', 'state_name' => 'Dubai', 'name' => 'Dubai'],
            ['country_code' => 'AE', 'state_name' => 'Abu Dhabi', 'name' => 'Abu Dhabi'],
            ['country_code' => 'AE', 'state_name' => 'Sharjah', 'name' => 'Sharjah'],
            ['country_code' => 'AE', 'state_name' => 'Ajman', 'name' => 'Ajman'],
            ['country_code' => 'AE', 'state_name' => 'Ras Al Khaimah', 'name' => 'Ras Al Khaimah'],
            ['country_code' => 'AE', 'state_name' => 'Fujairah', 'name' => 'Fujairah'],
            ['country_code' => 'AE', 'state_name' => 'Umm Al-Quwain', 'name' => 'Umm Al-Quwain'],

            // ==================== SAUDI ARABIA ====================
            ['country_code' => 'SA', 'state_name' => 'Riyadh', 'name' => 'Riyadh'],
            ['country_code' => 'SA', 'state_name' => 'Mecca', 'name' => 'Jeddah'],
            ['country_code' => 'SA', 'state_name' => 'Mecca', 'name' => 'Mecca'],
            ['country_code' => 'SA', 'state_name' => 'Medina', 'name' => 'Medina'],
            ['country_code' => 'SA', 'state_name' => 'Eastern Province', 'name' => 'Dammam'],
            ['country_code' => 'SA', 'state_name' => 'Eastern Province', 'name' => 'Khobar'],
            ['country_code' => 'SA', 'state_name' => 'Asir', 'name' => 'Abha'],
            ['country_code' => 'SA', 'state_name' => 'Tabuk', 'name' => 'Tabuk'],

            // ==================== CANADA ====================
            ['country_code' => 'CA', 'state_name' => 'Ontario', 'name' => 'Toronto'],
            ['country_code' => 'CA', 'state_name' => 'Quebec', 'name' => 'Montreal'],
            ['country_code' => 'CA', 'state_name' => 'British Columbia', 'name' => 'Vancouver'],
            ['country_code' => 'CA', 'state_name' => 'Alberta', 'name' => 'Calgary'],
            ['country_code' => 'CA', 'state_name' => 'Ontario', 'name' => 'Ottawa'],
            ['country_code' => 'CA', 'state_name' => 'Alberta', 'name' => 'Edmonton'],
            ['country_code' => 'CA', 'state_name' => 'Manitoba', 'name' => 'Winnipeg'],
            ['country_code' => 'CA', 'state_name' => 'Quebec', 'name' => 'Quebec City'],
            ['country_code' => 'CA', 'state_name' => 'Saskatchewan', 'name' => 'Regina'],

            // ==================== AUSTRALIA ====================
            ['country_code' => 'AU', 'state_name' => 'New South Wales', 'name' => 'Sydney'],
            ['country_code' => 'AU', 'state_name' => 'Victoria', 'name' => 'Melbourne'],
            ['country_code' => 'AU', 'state_name' => 'Queensland', 'name' => 'Brisbane'],
            ['country_code' => 'AU', 'state_name' => 'Western Australia', 'name' => 'Perth'],
            ['country_code' => 'AU', 'state_name' => 'South Australia', 'name' => 'Adelaide'],
            ['country_code' => 'AU', 'state_name' => 'New South Wales', 'name' => 'Canberra'],

            // ==================== NETHERLANDS ====================
            ['country_code' => 'NL', 'state_name' => 'North Holland', 'name' => 'Amsterdam'],
            ['country_code' => 'NL', 'state_name' => 'South Holland', 'name' => 'Rotterdam'],
            ['country_code' => 'NL', 'state_name' => 'South Holland', 'name' => 'The Hague'],
            ['country_code' => 'NL', 'state_name' => 'Utrecht', 'name' => 'Utrecht'],
            ['country_code' => 'NL', 'state_name' => 'North Brabant', 'name' => 'Eindhoven'],
            ['country_code' => 'NL', 'state_name' => 'Gelderland', 'name' => 'Nijmegen'],

            // ==================== SINGAPORE ====================
            ['country_code' => 'SG', 'state_name' => 'Central Region', 'name' => 'Singapore'],

            // ==================== SOUTH KOREA ====================
            ['country_code' => 'KR', 'state_name' => 'Seoul', 'name' => 'Seoul'],
            ['country_code' => 'KR', 'state_name' => 'Busan', 'name' => 'Busan'],
            ['country_code' => 'KR', 'state_name' => 'Incheon', 'name' => 'Incheon'],
            ['country_code' => 'KR', 'state_name' => 'Daegu', 'name' => 'Daegu'],
            ['country_code' => 'KR', 'state_name' => 'Daejeon', 'name' => 'Daejeon'],
            ['country_code' => 'KR', 'state_name' => 'Gwangju', 'name' => 'Gwangju'],

            // ==================== MALAYSIA ====================
            ['country_code' => 'MY', 'state_name' => 'Kuala Lumpur', 'name' => 'Kuala Lumpur'],
            ['country_code' => 'MY', 'state_name' => 'Selangor', 'name' => 'Shah Alam'],
            ['country_code' => 'MY', 'state_name' => 'Johor', 'name' => 'Johor Bahru'],
            ['country_code' => 'MY', 'state_name' => 'Penang', 'name' => 'George Town'],
            ['country_code' => 'MY', 'state_name' => 'Sarawak', 'name' => 'Kuching'],
            ['country_code' => 'MY', 'state_name' => 'Sabah', 'name' => 'Kota Kinabalu'],

            // ==================== IRELAND ====================
            ['country_code' => 'IE', 'state_name' => 'Dublin', 'name' => 'Dublin'],
            ['country_code' => 'IE', 'state_name' => 'Cork', 'name' => 'Cork'],
            ['country_code' => 'IE', 'state_name' => 'Galway', 'name' => 'Galway'],
            ['country_code' => 'IE', 'state_name' => 'Limerick', 'name' => 'Limerick'],

            // ==================== QATAR ====================
            ['country_code' => 'QA', 'state_name' => 'Doha', 'name' => 'Doha'],
            ['country_code' => 'QA', 'state_name' => 'Al Rayyan', 'name' => 'Al Rayyan'],
            ['country_code' => 'QA', 'state_name' => 'Al Wakrah', 'name' => 'Al Wakrah'],
            ['country_code' => 'QA', 'state_name' => 'Al Khor', 'name' => 'Al Khor'],

            // ==================== KUWAIT ====================
            ['country_code' => 'KW', 'state_name' => 'Capital', 'name' => 'Kuwait City'],
            ['country_code' => 'KW', 'state_name' => 'Hawalli', 'name' => 'Hawalli'],
            ['country_code' => 'KW', 'state_name' => 'Ahmadi', 'name' => 'Ahmadi'],
            ['country_code' => 'KW', 'state_name' => 'Farwaniya', 'name' => 'Farwaniya'],
            ['country_code' => 'KW', 'state_name' => 'Jahra', 'name' => 'Jahra'],

            // ==================== NEW ZEALAND ====================
            ['country_code' => 'NZ', 'state_name' => 'Auckland', 'name' => 'Auckland'],
            ['country_code' => 'NZ', 'state_name' => 'Wellington', 'name' => 'Wellington'],
            ['country_code' => 'NZ', 'state_name' => 'Canterbury', 'name' => 'Christchurch'],
            ['country_code' => 'NZ', 'state_name' => 'Waikato', 'name' => 'Hamilton'],
            ['country_code' => 'NZ', 'state_name' => 'Otago', 'name' => 'Dunedin'],

            // ==================== SWITZERLAND ====================
            ['country_code' => 'CH', 'state_name' => 'Zürich', 'name' => 'Zürich'],
            ['country_code' => 'CH', 'state_name' => 'Bern', 'name' => 'Bern'],
            ['country_code' => 'CH', 'state_name' => 'Geneva', 'name' => 'Geneva'],
            ['country_code' => 'CH', 'state_name' => 'Basel-Stadt', 'name' => 'Basel'],
            ['country_code' => 'CH', 'state_name' => 'Lucerne', 'name' => 'Lucerne'],
            ['country_code' => 'CH', 'state_name' => 'Lausanne', 'name' => 'Lausanne'],

            // ==================== JAPAN ====================
            ['country_code' => 'JP', 'state_name' => 'Tokyo', 'name' => 'Tokyo'],
            ['country_code' => 'JP', 'state_name' => 'Osaka', 'name' => 'Osaka'],
            ['country_code' => 'JP', 'state_name' => 'Kanagawa', 'name' => 'Yokohama'],
            ['country_code' => 'JP', 'state_name' => 'Aichi', 'name' => 'Nagoya'],
            ['country_code' => 'JP', 'state_name' => 'Hokkaido', 'name' => 'Sapporo'],
            ['country_code' => 'JP', 'state_name' => 'Fukuoka', 'name' => 'Fukuoka'],
            ['country_code' => 'JP', 'state_name' => 'Kyoto', 'name' => 'Kyoto'],
            ['country_code' => 'JP', 'state_name' => 'Hyogo', 'name' => 'Kobe'],
        ];
    }
}
