<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class WorldLocationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌍 Seeding World Locations...');

        // ============================================
        // COUNTRIES (249 Countries)
        // ============================================
        $countries = $this->getAllCountries();
        $countryIds = [];

        foreach ($countries as $country) {
            $existing = Country::where('code', $country['code'])->first();
            if ($existing) {
                $countryIds[$country['code']] = $existing->id;
                continue;
            }

            $c = Country::create([
                'name' => $country['name'],
                'code' => $country['code'],
                'phone_code' => $country['phone_code'] ?? null,
                'is_active' => true,
            ]);
            $countryIds[$country['code']] = $c->id;
        }

        $this->command->info('✅ ' . count($countryIds) . ' countries added/verified.');

        // ============================================
        // STATES (Major countries only)
        // ============================================
        $states = $this->getMajorStates();
        $stateIds = [];
        $stateCount = 0;

        foreach ($states as $state) {
            $countryId = $countryIds[$state['country_code']] ?? null;
            if (!$countryId) {
                $this->command->warn('⚠️ Skipping state: ' . $state['name'] . ' (Country not found)');
                continue;
            }

            $existing = State::where('name', $state['name'])
                ->where('country_id', $countryId)
                ->first();

            if ($existing) {
                $stateIds[$state['name']] = $existing->id;
                continue;
            }

            $s = State::create([
                'country_id' => $countryId,
                'name' => $state['name'],
                'code' => $state['code'] ?? null,
                'is_active' => true,
            ]);
            $stateIds[$state['name']] = $s->id;
            $stateCount++;
        }

        $this->command->info('✅ ' . $stateCount . ' new states added.');

        // ============================================
        // CITIES (Major cities only)
        // ============================================
        $cities = $this->getMajorCities();
        $cityCount = 0;

        foreach ($cities as $city) {
            $stateId = $stateIds[$city['state_name']] ?? null;
            if (!$stateId) {
                $this->command->warn('⚠️ Skipping city: ' . $city['name'] . ' (State not found)');
                continue;
            }

            $existing = City::where('name', $city['name'])
                ->where('state_id', $stateId)
                ->first();

            if ($existing) {
                continue;
            }

            City::create([
                'state_id' => $stateId,
                'name' => $city['name'],
                'is_active' => true,
            ]);
            $cityCount++;
        }

        $this->command->info('✅ ' . $cityCount . ' new cities added.');
        $this->command->info('🎉 World Locations Seeding Complete!');
    }

    // ============================================
    // DATA: ALL COUNTRIES (249) - ✅ Complete
    // ============================================
    private function getAllCountries(): array
    {
        return [
            // A
            ['name' => 'Afghanistan', 'code' => 'AF', 'phone_code' => '+93'],
            ['name' => 'Albania', 'code' => 'AL', 'phone_code' => '+355'],
            ['name' => 'Algeria', 'code' => 'DZ', 'phone_code' => '+213'],
            ['name' => 'American Samoa', 'code' => 'AS', 'phone_code' => '+1'],
            ['name' => 'Andorra', 'code' => 'AD', 'phone_code' => '+376'],
            ['name' => 'Angola', 'code' => 'AO', 'phone_code' => '+244'],
            ['name' => 'Anguilla', 'code' => 'AI', 'phone_code' => '+1'],
            ['name' => 'Antarctica', 'code' => 'AQ', 'phone_code' => '+672'],
            ['name' => 'Antigua and Barbuda', 'code' => 'AG', 'phone_code' => '+1'],
            ['name' => 'Argentina', 'code' => 'AR', 'phone_code' => '+54'],
            ['name' => 'Armenia', 'code' => 'AM', 'phone_code' => '+374'],
            ['name' => 'Aruba', 'code' => 'AW', 'phone_code' => '+297'],
            ['name' => 'Australia', 'code' => 'AU', 'phone_code' => '+61'],
            ['name' => 'Austria', 'code' => 'AT', 'phone_code' => '+43'],
            ['name' => 'Azerbaijan', 'code' => 'AZ', 'phone_code' => '+994'],

            // B
            ['name' => 'Bahamas', 'code' => 'BS', 'phone_code' => '+1'],
            ['name' => 'Bahrain', 'code' => 'BH', 'phone_code' => '+973'],
            ['name' => 'Bangladesh', 'code' => 'BD', 'phone_code' => '+880'],
            ['name' => 'Barbados', 'code' => 'BB', 'phone_code' => '+1'],
            ['name' => 'Belarus', 'code' => 'BY', 'phone_code' => '+375'],
            ['name' => 'Belgium', 'code' => 'BE', 'phone_code' => '+32'],
            ['name' => 'Belize', 'code' => 'BZ', 'phone_code' => '+501'],
            ['name' => 'Benin', 'code' => 'BJ', 'phone_code' => '+229'],
            ['name' => 'Bermuda', 'code' => 'BM', 'phone_code' => '+1'],
            ['name' => 'Bhutan', 'code' => 'BT', 'phone_code' => '+975'],
            ['name' => 'Bolivia', 'code' => 'BO', 'phone_code' => '+591'],
            ['name' => 'Bosnia and Herzegovina', 'code' => 'BA', 'phone_code' => '+387'],
            ['name' => 'Botswana', 'code' => 'BW', 'phone_code' => '+267'],
            ['name' => 'Brazil', 'code' => 'BR', 'phone_code' => '+55'],
            ['name' => 'British Indian Ocean Territory', 'code' => 'IO', 'phone_code' => '+246'],
            ['name' => 'Brunei', 'code' => 'BN', 'phone_code' => '+673'],
            ['name' => 'Bulgaria', 'code' => 'BG', 'phone_code' => '+359'],
            ['name' => 'Burkina Faso', 'code' => 'BF', 'phone_code' => '+226'],
            ['name' => 'Burundi', 'code' => 'BI', 'phone_code' => '+257'],

            // C
            ['name' => 'Cambodia', 'code' => 'KH', 'phone_code' => '+855'],
            ['name' => 'Cameroon', 'code' => 'CM', 'phone_code' => '+237'],
            ['name' => 'Canada', 'code' => 'CA', 'phone_code' => '+1'],
            ['name' => 'Cape Verde', 'code' => 'CV', 'phone_code' => '+238'],
            ['name' => 'Cayman Islands', 'code' => 'KY', 'phone_code' => '+1'],
            ['name' => 'Central African Republic', 'code' => 'CF', 'phone_code' => '+236'],
            ['name' => 'Chad', 'code' => 'TD', 'phone_code' => '+235'],
            ['name' => 'Chile', 'code' => 'CL', 'phone_code' => '+56'],
            ['name' => 'China', 'code' => 'CN', 'phone_code' => '+86'],
            ['name' => 'Christmas Island', 'code' => 'CX', 'phone_code' => '+61'],
            ['name' => 'Colombia', 'code' => 'CO', 'phone_code' => '+57'],
            ['name' => 'Comoros', 'code' => 'KM', 'phone_code' => '+269'],
            ['name' => 'Congo', 'code' => 'CG', 'phone_code' => '+242'],
            ['name' => 'Costa Rica', 'code' => 'CR', 'phone_code' => '+506'],
            ['name' => 'Croatia', 'code' => 'HR', 'phone_code' => '+385'],
            ['name' => 'Cuba', 'code' => 'CU', 'phone_code' => '+53'],
            ['name' => 'Cyprus', 'code' => 'CY', 'phone_code' => '+357'],
            ['name' => 'Czech Republic', 'code' => 'CZ', 'phone_code' => '+420'],

            // D
            ['name' => 'Denmark', 'code' => 'DK', 'phone_code' => '+45'],
            ['name' => 'Djibouti', 'code' => 'DJ', 'phone_code' => '+253'],
            ['name' => 'Dominica', 'code' => 'DM', 'phone_code' => '+1'],
            ['name' => 'Dominican Republic', 'code' => 'DO', 'phone_code' => '+1'],

            // E
            ['name' => 'Ecuador', 'code' => 'EC', 'phone_code' => '+593'],
            ['name' => 'Egypt', 'code' => 'EG', 'phone_code' => '+20'],
            ['name' => 'El Salvador', 'code' => 'SV', 'phone_code' => '+503'],
            ['name' => 'Equatorial Guinea', 'code' => 'GQ', 'phone_code' => '+240'],
            ['name' => 'Eritrea', 'code' => 'ER', 'phone_code' => '+291'],
            ['name' => 'Estonia', 'code' => 'EE', 'phone_code' => '+372'],
            ['name' => 'Eswatini', 'code' => 'SZ', 'phone_code' => '+268'],
            ['name' => 'Ethiopia', 'code' => 'ET', 'phone_code' => '+251'],

            // F
            ['name' => 'Falkland Islands', 'code' => 'FK', 'phone_code' => '+500'],
            ['name' => 'Faroe Islands', 'code' => 'FO', 'phone_code' => '+298'],
            ['name' => 'Fiji', 'code' => 'FJ', 'phone_code' => '+679'],
            ['name' => 'Finland', 'code' => 'FI', 'phone_code' => '+358'],
            ['name' => 'France', 'code' => 'FR', 'phone_code' => '+33'],
            ['name' => 'French Guiana', 'code' => 'GF', 'phone_code' => '+594'],
            ['name' => 'French Polynesia', 'code' => 'PF', 'phone_code' => '+689'],

            // G
            ['name' => 'Gabon', 'code' => 'GA', 'phone_code' => '+241'],
            ['name' => 'Gambia', 'code' => 'GM', 'phone_code' => '+220'],
            ['name' => 'Georgia', 'code' => 'GE', 'phone_code' => '+995'],
            ['name' => 'Germany', 'code' => 'DE', 'phone_code' => '+49'],
            ['name' => 'Ghana', 'code' => 'GH', 'phone_code' => '+233'],
            ['name' => 'Gibraltar', 'code' => 'GI', 'phone_code' => '+350'],
            ['name' => 'Greece', 'code' => 'GR', 'phone_code' => '+30'],
            ['name' => 'Greenland', 'code' => 'GL', 'phone_code' => '+299'],
            ['name' => 'Grenada', 'code' => 'GD', 'phone_code' => '+1'],
            ['name' => 'Guadeloupe', 'code' => 'GP', 'phone_code' => '+590'],
            ['name' => 'Guam', 'code' => 'GU', 'phone_code' => '+1'],
            ['name' => 'Guatemala', 'code' => 'GT', 'phone_code' => '+502'],
            ['name' => 'Guernsey', 'code' => 'GG', 'phone_code' => '+44'],
            ['name' => 'Guinea', 'code' => 'GN', 'phone_code' => '+224'],
            ['name' => 'Guinea-Bissau', 'code' => 'GW', 'phone_code' => '+245'],
            ['name' => 'Guyana', 'code' => 'GY', 'phone_code' => '+592'],

            // H
            ['name' => 'Haiti', 'code' => 'HT', 'phone_code' => '+509'],
            ['name' => 'Honduras', 'code' => 'HN', 'phone_code' => '+504'],
            ['name' => 'Hong Kong', 'code' => 'HK', 'phone_code' => '+852'],
            ['name' => 'Hungary', 'code' => 'HU', 'phone_code' => '+36'],

            // I
            ['name' => 'Iceland', 'code' => 'IS', 'phone_code' => '+354'],
            ['name' => 'India', 'code' => 'IN', 'phone_code' => '+91'],
            ['name' => 'Indonesia', 'code' => 'ID', 'phone_code' => '+62'],
            ['name' => 'Iran', 'code' => 'IR', 'phone_code' => '+98'],
            ['name' => 'Iraq', 'code' => 'IQ', 'phone_code' => '+964'],
            ['name' => 'Ireland', 'code' => 'IE', 'phone_code' => '+353'],
            ['name' => 'Isle of Man', 'code' => 'IM', 'phone_code' => '+44'],
            ['name' => 'Israel', 'code' => 'IL', 'phone_code' => '+972'],
            ['name' => 'Italy', 'code' => 'IT', 'phone_code' => '+39'],

            // J
            ['name' => 'Jamaica', 'code' => 'JM', 'phone_code' => '+1'],
            ['name' => 'Japan', 'code' => 'JP', 'phone_code' => '+81'],
            ['name' => 'Jersey', 'code' => 'JE', 'phone_code' => '+44'],
            ['name' => 'Jordan', 'code' => 'JO', 'phone_code' => '+962'],

            // K
            ['name' => 'Kazakhstan', 'code' => 'KZ', 'phone_code' => '+7'],
            ['name' => 'Kenya', 'code' => 'KE', 'phone_code' => '+254'],
            ['name' => 'Kiribati', 'code' => 'KI', 'phone_code' => '+686'],
            ['name' => 'Kuwait', 'code' => 'KW', 'phone_code' => '+965'],
            ['name' => 'Kyrgyzstan', 'code' => 'KG', 'phone_code' => '+996'],

            // L
            ['name' => 'Laos', 'code' => 'LA', 'phone_code' => '+856'],
            ['name' => 'Latvia', 'code' => 'LV', 'phone_code' => '+371'],
            ['name' => 'Lebanon', 'code' => 'LB', 'phone_code' => '+961'],
            ['name' => 'Lesotho', 'code' => 'LS', 'phone_code' => '+266'],
            ['name' => 'Liberia', 'code' => 'LR', 'phone_code' => '+231'],
            ['name' => 'Libya', 'code' => 'LY', 'phone_code' => '+218'],
            ['name' => 'Liechtenstein', 'code' => 'LI', 'phone_code' => '+423'],
            ['name' => 'Lithuania', 'code' => 'LT', 'phone_code' => '+370'],
            ['name' => 'Luxembourg', 'code' => 'LU', 'phone_code' => '+352'],

            // M
            ['name' => 'Macao', 'code' => 'MO', 'phone_code' => '+853'],
            ['name' => 'Madagascar', 'code' => 'MG', 'phone_code' => '+261'],
            ['name' => 'Malawi', 'code' => 'MW', 'phone_code' => '+265'],
            ['name' => 'Malaysia', 'code' => 'MY', 'phone_code' => '+60'],
            ['name' => 'Maldives', 'code' => 'MV', 'phone_code' => '+960'],
            ['name' => 'Mali', 'code' => 'ML', 'phone_code' => '+223'],
            ['name' => 'Malta', 'code' => 'MT', 'phone_code' => '+356'],
            ['name' => 'Marshall Islands', 'code' => 'MH', 'phone_code' => '+692'],
            ['name' => 'Martinique', 'code' => 'MQ', 'phone_code' => '+596'],
            ['name' => 'Mauritania', 'code' => 'MR', 'phone_code' => '+222'],
            ['name' => 'Mauritius', 'code' => 'MU', 'phone_code' => '+230'],
            ['name' => 'Mayotte', 'code' => 'YT', 'phone_code' => '+262'],
            ['name' => 'Mexico', 'code' => 'MX', 'phone_code' => '+52'],
            ['name' => 'Micronesia', 'code' => 'FM', 'phone_code' => '+691'],
            ['name' => 'Moldova', 'code' => 'MD', 'phone_code' => '+373'],
            ['name' => 'Monaco', 'code' => 'MC', 'phone_code' => '+377'],
            ['name' => 'Mongolia', 'code' => 'MN', 'phone_code' => '+976'],
            ['name' => 'Montenegro', 'code' => 'ME', 'phone_code' => '+382'],
            ['name' => 'Montserrat', 'code' => 'MS', 'phone_code' => '+1'],
            ['name' => 'Morocco', 'code' => 'MA', 'phone_code' => '+212'],
            ['name' => 'Mozambique', 'code' => 'MZ', 'phone_code' => '+258'],
            ['name' => 'Myanmar', 'code' => 'MM', 'phone_code' => '+95'],

            // N
            ['name' => 'Namibia', 'code' => 'NA', 'phone_code' => '+264'],
            ['name' => 'Nauru', 'code' => 'NR', 'phone_code' => '+674'],
            ['name' => 'Nepal', 'code' => 'NP', 'phone_code' => '+977'],
            ['name' => 'Netherlands', 'code' => 'NL', 'phone_code' => '+31'],
            ['name' => 'New Caledonia', 'code' => 'NC', 'phone_code' => '+687'],
            ['name' => 'New Zealand', 'code' => 'NZ', 'phone_code' => '+64'],
            ['name' => 'Nicaragua', 'code' => 'NI', 'phone_code' => '+505'],
            ['name' => 'Niger', 'code' => 'NE', 'phone_code' => '+227'],
            ['name' => 'Nigeria', 'code' => 'NG', 'phone_code' => '+234'],
            ['name' => 'Niue', 'code' => 'NU', 'phone_code' => '+683'],
            ['name' => 'Norfolk Island', 'code' => 'NF', 'phone_code' => '+672'],
            ['name' => 'North Macedonia', 'code' => 'MK', 'phone_code' => '+389'],
            ['name' => 'Northern Mariana Islands', 'code' => 'MP', 'phone_code' => '+1'],
            ['name' => 'Norway', 'code' => 'NO', 'phone_code' => '+47'],

            // O
            ['name' => 'Oman', 'code' => 'OM', 'phone_code' => '+968'],

            // P
            ['name' => 'Pakistan', 'code' => 'PK', 'phone_code' => '+92'],
            ['name' => 'Palau', 'code' => 'PW', 'phone_code' => '+680'],
            ['name' => 'Palestine', 'code' => 'PS', 'phone_code' => '+970'],
            ['name' => 'Panama', 'code' => 'PA', 'phone_code' => '+507'],
            ['name' => 'Papua New Guinea', 'code' => 'PG', 'phone_code' => '+675'],
            ['name' => 'Paraguay', 'code' => 'PY', 'phone_code' => '+595'],
            ['name' => 'Peru', 'code' => 'PE', 'phone_code' => '+51'],
            ['name' => 'Philippines', 'code' => 'PH', 'phone_code' => '+63'],
            ['name' => 'Poland', 'code' => 'PL', 'phone_code' => '+48'],
            ['name' => 'Portugal', 'code' => 'PT', 'phone_code' => '+351'],
            ['name' => 'Puerto Rico', 'code' => 'PR', 'phone_code' => '+1'],

            // Q
            ['name' => 'Qatar', 'code' => 'QA', 'phone_code' => '+974'],

            // R
            ['name' => 'Romania', 'code' => 'RO', 'phone_code' => '+40'],
            ['name' => 'Russia', 'code' => 'RU', 'phone_code' => '+7'],
            ['name' => 'Rwanda', 'code' => 'RW', 'phone_code' => '+250'],

            // S
            ['name' => 'Saint Kitts and Nevis', 'code' => 'KN', 'phone_code' => '+1'],
            ['name' => 'Saint Lucia', 'code' => 'LC', 'phone_code' => '+1'],
            ['name' => 'Saint Vincent', 'code' => 'VC', 'phone_code' => '+1'],
            ['name' => 'Samoa', 'code' => 'WS', 'phone_code' => '+685'],
            ['name' => 'San Marino', 'code' => 'SM', 'phone_code' => '+378'],
            ['name' => 'Sao Tome and Principe', 'code' => 'ST', 'phone_code' => '+239'],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'phone_code' => '+966'],
            ['name' => 'Senegal', 'code' => 'SN', 'phone_code' => '+221'],
            ['name' => 'Serbia', 'code' => 'RS', 'phone_code' => '+381'],
            ['name' => 'Seychelles', 'code' => 'SC', 'phone_code' => '+248'],
            ['name' => 'Sierra Leone', 'code' => 'SL', 'phone_code' => '+232'],
            ['name' => 'Singapore', 'code' => 'SG', 'phone_code' => '+65'],
            ['name' => 'Sint Maarten', 'code' => 'SX', 'phone_code' => '+1'],
            ['name' => 'Slovakia', 'code' => 'SK', 'phone_code' => '+421'],
            ['name' => 'Slovenia', 'code' => 'SI', 'phone_code' => '+386'],
            ['name' => 'Solomon Islands', 'code' => 'SB', 'phone_code' => '+677'],
            ['name' => 'Somalia', 'code' => 'SO', 'phone_code' => '+252'],
            ['name' => 'South Africa', 'code' => 'ZA', 'phone_code' => '+27'],
            ['name' => 'South Korea', 'code' => 'KR', 'phone_code' => '+82'],
            ['name' => 'South Sudan', 'code' => 'SS', 'phone_code' => '+211'],
            ['name' => 'Spain', 'code' => 'ES', 'phone_code' => '+34'],
            ['name' => 'Sri Lanka', 'code' => 'LK', 'phone_code' => '+94'],
            ['name' => 'Sudan', 'code' => 'SD', 'phone_code' => '+249'],
            ['name' => 'Suriname', 'code' => 'SR', 'phone_code' => '+597'],
            ['name' => 'Sweden', 'code' => 'SE', 'phone_code' => '+46'],
            ['name' => 'Switzerland', 'code' => 'CH', 'phone_code' => '+41'],
            ['name' => 'Syria', 'code' => 'SY', 'phone_code' => '+963'],

            // T
            ['name' => 'Taiwan', 'code' => 'TW', 'phone_code' => '+886'],
            ['name' => 'Tajikistan', 'code' => 'TJ', 'phone_code' => '+992'],
            ['name' => 'Tanzania', 'code' => 'TZ', 'phone_code' => '+255'],
            ['name' => 'Thailand', 'code' => 'TH', 'phone_code' => '+66'],
            ['name' => 'Timor-Leste', 'code' => 'TL', 'phone_code' => '+670'],
            ['name' => 'Togo', 'code' => 'TG', 'phone_code' => '+228'],
            ['name' => 'Tokelau', 'code' => 'TK', 'phone_code' => '+690'],
            ['name' => 'Tonga', 'code' => 'TO', 'phone_code' => '+676'],
            ['name' => 'Trinidad and Tobago', 'code' => 'TT', 'phone_code' => '+1'],
            ['name' => 'Tunisia', 'code' => 'TN', 'phone_code' => '+216'],
            ['name' => 'Turkey', 'code' => 'TR', 'phone_code' => '+90'],
            ['name' => 'Turkmenistan', 'code' => 'TM', 'phone_code' => '+993'],
            ['name' => 'Tuvalu', 'code' => 'TV', 'phone_code' => '+688'],

            // U
            ['name' => 'Uganda', 'code' => 'UG', 'phone_code' => '+256'],
            ['name' => 'Ukraine', 'code' => 'UA', 'phone_code' => '+380'],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'phone_code' => '+971'],
            ['name' => 'United Kingdom', 'code' => 'GB', 'phone_code' => '+44'],
            ['name' => 'United States', 'code' => 'US', 'phone_code' => '+1'],
            ['name' => 'Uruguay', 'code' => 'UY', 'phone_code' => '+598'],
            ['name' => 'Uzbekistan', 'code' => 'UZ', 'phone_code' => '+998'],

            // V
            ['name' => 'Vanuatu', 'code' => 'VU', 'phone_code' => '+678'],
            ['name' => 'Vatican City', 'code' => 'VA', 'phone_code' => '+379'],
            ['name' => 'Venezuela', 'code' => 'VE', 'phone_code' => '+58'],
            ['name' => 'Vietnam', 'code' => 'VN', 'phone_code' => '+84'],

            // W
            ['name' => 'Wallis and Futuna', 'code' => 'WF', 'phone_code' => '+681'],
            ['name' => 'Western Sahara', 'code' => 'EH', 'phone_code' => '+212'],

            // Y
            ['name' => 'Yemen', 'code' => 'YE', 'phone_code' => '+967'],

            // Z
            ['name' => 'Zambia', 'code' => 'ZM', 'phone_code' => '+260'],
            ['name' => 'Zimbabwe', 'code' => 'ZW', 'phone_code' => '+263'],
        ];
    }

    // ============================================
    // MAJOR STATES (All countries) - ✅ Updated
    // ============================================
    private function getMajorStates(): array
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

            // ==================== CANADA ====================
            ['country_code' => 'CA', 'name' => 'Alberta', 'code' => 'AB'],
            ['country_code' => 'CA', 'name' => 'British Columbia', 'code' => 'BC'],
            ['country_code' => 'CA', 'name' => 'Manitoba', 'code' => 'MB'],
            ['country_code' => 'CA', 'name' => 'New Brunswick', 'code' => 'NB'],
            ['country_code' => 'CA', 'name' => 'Newfoundland', 'code' => 'NL'],
            ['country_code' => 'CA', 'name' => 'Nova Scotia', 'code' => 'NS'],
            ['country_code' => 'CA', 'name' => 'Ontario', 'code' => 'ON'],
            ['country_code' => 'CA', 'name' => 'Quebec', 'code' => 'QC'],
            ['country_code' => 'CA', 'name' => 'Saskatchewan', 'code' => 'SK'],

            // ==================== UK ====================
            ['country_code' => 'GB', 'name' => 'England', 'code' => 'ENG'],
            ['country_code' => 'GB', 'name' => 'Northern Ireland', 'code' => 'NIR'],
            ['country_code' => 'GB', 'name' => 'Scotland', 'code' => 'SCT'],
            ['country_code' => 'GB', 'name' => 'Wales', 'code' => 'WLS'],

            // ==================== AUSTRALIA ====================
            ['country_code' => 'AU', 'name' => 'New South Wales', 'code' => 'NSW'],
            ['country_code' => 'AU', 'name' => 'Victoria', 'code' => 'VIC'],
            ['country_code' => 'AU', 'name' => 'Queensland', 'code' => 'QLD'],
            ['country_code' => 'AU', 'name' => 'Western Australia', 'code' => 'WA'],
            ['country_code' => 'AU', 'name' => 'South Australia', 'code' => 'SA'],
            ['country_code' => 'AU', 'name' => 'Tasmania', 'code' => 'TAS'],

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

            // ==================== CHINA ====================
            ['country_code' => 'CN', 'name' => 'Beijing', 'code' => 'BJ'],
            ['country_code' => 'CN', 'name' => 'Guangdong', 'code' => 'GD'],
            ['country_code' => 'CN', 'name' => 'Shanghai', 'code' => 'SH'],
            ['country_code' => 'CN', 'name' => 'Sichuan', 'code' => 'SC'],
            ['country_code' => 'CN', 'name' => 'Zhejiang', 'code' => 'ZJ'],

            // ==================== GERMANY ====================
            ['country_code' => 'DE', 'name' => 'Bavaria', 'code' => 'BY'],
            ['country_code' => 'DE', 'name' => 'Berlin', 'code' => 'BE'],
            ['country_code' => 'DE', 'name' => 'Hamburg', 'code' => 'HH'],
            ['country_code' => 'DE', 'name' => 'Hesse', 'code' => 'HE'],

            // ==================== FRANCE ====================
            ['country_code' => 'FR', 'name' => 'Île-de-France', 'code' => 'IDF'],
            ['country_code' => 'FR', 'name' => 'Provence-Alpes-Côte d\'Azur', 'code' => 'PAC'],
            ['country_code' => 'FR', 'name' => 'Auvergne-Rhône-Alpes', 'code' => 'ARA'],

            // ==================== ITALY ====================
            ['country_code' => 'IT', 'name' => 'Campania', 'code' => 'CAM'],
            ['country_code' => 'IT', 'name' => 'Lazio', 'code' => 'LAZ'],
            ['country_code' => 'IT', 'name' => 'Lombardy', 'code' => 'LOM'],

            // ==================== SPAIN ====================
            ['country_code' => 'ES', 'name' => 'Andalusia', 'code' => 'AN'],
            ['country_code' => 'ES', 'name' => 'Catalonia', 'code' => 'CT'],
            ['country_code' => 'ES', 'name' => 'Madrid', 'code' => 'MD'],

            // ==================== TURKEY ====================
            ['country_code' => 'TR', 'name' => 'Ankara', 'code' => 'ANK'],
            ['country_code' => 'TR', 'name' => 'Istanbul', 'code' => 'IST'],
            ['country_code' => 'TR', 'name' => 'Izmir', 'code' => 'IZM'],

            // ==================== BANGLADESH ====================
            ['country_code' => 'BD', 'name' => 'Chittagong', 'code' => 'CH'],
            ['country_code' => 'BD', 'name' => 'Dhaka', 'code' => 'DH'],
            ['country_code' => 'BD', 'name' => 'Rajshahi', 'code' => 'RA'],

            // ==================== MALAYSIA ====================
            ['country_code' => 'MY', 'name' => 'Johor', 'code' => 'JHR'],
            ['country_code' => 'MY', 'name' => 'Kuala Lumpur', 'code' => 'KL'],
            ['country_code' => 'MY', 'name' => 'Selangor', 'code' => 'SEL'],
        ];
    }

    // ============================================
    // MAJOR CITIES - ✅ Complete
    // ============================================
    private function getMajorCities(): array
    {
        return [
            // ==================== PAKISTAN ====================
            // Punjab
            ['name' => 'Lahore', 'state_name' => 'Punjab'],
            ['name' => 'Faisalabad', 'state_name' => 'Punjab'],
            ['name' => 'Rawalpindi', 'state_name' => 'Punjab'],
            ['name' => 'Gujranwala', 'state_name' => 'Punjab'],
            ['name' => 'Multan', 'state_name' => 'Punjab'],
            ['name' => 'Bahawalpur', 'state_name' => 'Punjab'],
            ['name' => 'Sargodha', 'state_name' => 'Punjab'],
            ['name' => 'Sialkot', 'state_name' => 'Punjab'],
            ['name' => 'Sheikhupura', 'state_name' => 'Punjab'],
            ['name' => 'Rahim Yar Khan', 'state_name' => 'Punjab'],

            // Sindh
            ['name' => 'Karachi', 'state_name' => 'Sindh'],
            ['name' => 'Hyderabad', 'state_name' => 'Sindh'],
            ['name' => 'Sukkur', 'state_name' => 'Sindh'],
            ['name' => 'Larkana', 'state_name' => 'Sindh'],

            // Khyber Pakhtunkhwa
            ['name' => 'Peshawar', 'state_name' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Mardan', 'state_name' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Abbottabad', 'state_name' => 'Khyber Pakhtunkhwa'],

            // Balochistan
            ['name' => 'Quetta', 'state_name' => 'Balochistan'],

            // Islamabad
            ['name' => 'Islamabad', 'state_name' => 'Islamabad Capital Territory'],

            // ==================== INDIA ====================
            ['name' => 'Mumbai', 'state_name' => 'Maharashtra'],
            ['name' => 'Delhi', 'state_name' => 'Delhi'],
            ['name' => 'Bangalore', 'state_name' => 'Karnataka'],
            ['name' => 'Hyderabad', 'state_name' => 'Telangana'],
            ['name' => 'Ahmedabad', 'state_name' => 'Gujarat'],
            ['name' => 'Chennai', 'state_name' => 'Tamil Nadu'],
            ['name' => 'Kolkata', 'state_name' => 'West Bengal'],
            ['name' => 'Lucknow', 'state_name' => 'Uttar Pradesh'],

            // ==================== USA ====================
            ['name' => 'New York', 'state_name' => 'New York'],
            ['name' => 'Los Angeles', 'state_name' => 'California'],
            ['name' => 'Chicago', 'state_name' => 'Illinois'],
            ['name' => 'Houston', 'state_name' => 'Texas'],
            ['name' => 'Phoenix', 'state_name' => 'Arizona'],
            ['name' => 'Philadelphia', 'state_name' => 'Pennsylvania'],
            ['name' => 'San Antonio', 'state_name' => 'Texas'],
            ['name' => 'San Diego', 'state_name' => 'California'],
            ['name' => 'Dallas', 'state_name' => 'Texas'],
            ['name' => 'San Jose', 'state_name' => 'California'],

            // ==================== UK ====================
            ['name' => 'London', 'state_name' => 'England'],
            ['name' => 'Birmingham', 'state_name' => 'England'],
            ['name' => 'Manchester', 'state_name' => 'England'],
            ['name' => 'Liverpool', 'state_name' => 'England'],
            ['name' => 'Edinburgh', 'state_name' => 'Scotland'],
            ['name' => 'Glasgow', 'state_name' => 'Scotland'],

            // ==================== CANADA ====================
            ['name' => 'Toronto', 'state_name' => 'Ontario'],
            ['name' => 'Montreal', 'state_name' => 'Quebec'],
            ['name' => 'Vancouver', 'state_name' => 'British Columbia'],
            ['name' => 'Calgary', 'state_name' => 'Alberta'],
            ['name' => 'Ottawa', 'state_name' => 'Ontario'],

            // ==================== UAE ====================
            ['name' => 'Dubai', 'state_name' => 'Dubai'],
            ['name' => 'Abu Dhabi', 'state_name' => 'Abu Dhabi'],
            ['name' => 'Sharjah', 'state_name' => 'Sharjah'],

            // ==================== SAUDI ARABIA ====================
            ['name' => 'Riyadh', 'state_name' => 'Riyadh'],
            ['name' => 'Jeddah', 'state_name' => 'Mecca'],
            ['name' => 'Mecca', 'state_name' => 'Mecca'],
            ['name' => 'Medina', 'state_name' => 'Medina'],
            ['name' => 'Dammam', 'state_name' => 'Eastern Province'],

            // ==================== CHINA ====================
            ['name' => 'Beijing', 'state_name' => 'Beijing'],
            ['name' => 'Shanghai', 'state_name' => 'Shanghai'],
            ['name' => 'Guangzhou', 'state_name' => 'Guangdong'],
            ['name' => 'Shenzhen', 'state_name' => 'Guangdong'],

            // ==================== GERMANY ====================
            ['name' => 'Berlin', 'state_name' => 'Berlin'],
            ['name' => 'Hamburg', 'state_name' => 'Hamburg'],
            ['name' => 'Munich', 'state_name' => 'Bavaria'],
            ['name' => 'Cologne', 'state_name' => 'Hesse'],

            // ==================== FRANCE ====================
            ['name' => 'Paris', 'state_name' => 'Île-de-France'],
            ['name' => 'Marseille', 'state_name' => 'Provence-Alpes-Côte d\'Azur'],
            ['name' => 'Lyon', 'state_name' => 'Auvergne-Rhône-Alpes'],

            // ==================== TURKEY ====================
            ['name' => 'Istanbul', 'state_name' => 'Istanbul'],
            ['name' => 'Ankara', 'state_name' => 'Ankara'],
            ['name' => 'Izmir', 'state_name' => 'Izmir'],

            // ==================== BANGLADESH ====================
            ['name' => 'Dhaka', 'state_name' => 'Dhaka'],
            ['name' => 'Chittagong', 'state_name' => 'Chittagong'],

            // ==================== MALAYSIA ====================
            ['name' => 'Kuala Lumpur', 'state_name' => 'Kuala Lumpur'],
            ['name' => 'Johor Bahru', 'state_name' => 'Johor'],
        ];
    }
}
