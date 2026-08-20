<?php
// app/Console/Commands/ImportWorldLocations.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportWorldLocations extends Command
{
    protected $signature = 'import:world-locations
                            {--truncate : Truncate existing tables before import}
                            {--skip-ssl : Skip SSL verification}';

    protected $description = 'Import selected world countries, states, and cities from API Safely';

    public function handle()
    {
        $this->info('🌍 Importing Selected World Locations...');

        $skipSSL = $this->option('skip-ssl');
        $apiKey = 'fa243679aa2185a776759e6234436fdc0ffa777cb4bad470c56177764c3f57b7';

        // Targeted Countries List
        $majorCountries = [
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'DE' => 'Germany',
            'NL' => 'Netherlands',
            'SG' => 'Singapore',
            'PK' => 'Pakistan',
            'KR' => 'South Korea',
            'MY' => 'Malaysia',
            'IE' => 'Ireland',
            'QA' => 'Qatar',
            'KW' => 'Kuwait',
            'NZ' => 'New Zealand',
            'CH' => 'Switzerland',
            'JP' => 'Japan'
        ];

        $allowedCodes = array_keys($majorCountries);

        // ============================================
        // ✅ TRUNCATE TABLES SAFELY
        // ============================================
        if ($this->option('truncate')) {
            $this->info('⚠️ Truncating existing tables...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            try {
                DB::table('cities')->truncate();
                DB::table('states')->truncate();
                DB::table('countries')->truncate();
                $this->info('✅ Tables truncated successfully.');
            } catch (\Exception $e) {
                $this->error('❌ Truncate failed: ' . $e->getMessage());
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }

        // ============================================
        // 1. COUNTRIES IMPORT
        // ============================================
        $this->info('📥 Fetching countries from API...');

        try {
            $http = Http::timeout(60);
            if ($skipSSL) {
                $http->withOptions(['verify' => false]);
                $this->warn('⚠️ SSL verification disabled');
            }

            $response = $http->withHeaders([
                'X-CSCAPI-KEY' => $apiKey,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'application/json',
            ])->get('https://countrystatecity.in');

            $this->info('📊 API Response Status: ' . $response->status());

            if ($response->status() !== 200) {
                $this->error('❌ API returned status: ' . $response->status());
                $this->warn('💡 Raw Response: ' . substr($response->body(), 0, 500));
                return;
            }

            $countriesData = $response->json();

            // 🛑 CRASH PROTECTION FIXED: Check agar data empty ya array nahi hai
            if (empty($countriesData) || !is_array($countriesData)) {
                $this->error('❌ No valid array data received from API (Data is NULL or Blocked).');
                $this->warn('💡 API Body Response: ' . substr($response->body(), 0, 500));
                return;
            }

        } catch (\Exception $e) {
            $this->error('❌ API Connection Error: ' . $e->getMessage());
            return;
        }

        $countryMap = [];
        $countryCount = 0;

        foreach ($countriesData as $country) {
            if (!is_array($country)) {
                continue;
            }

            $code = $country['iso2'] ?? null;
            if (!$code || !in_array($code, $allowedCodes)) {
                continue;
            }

            $name = $country['name'] ?? null;
            $phoneCode = $country['phone_code'] ?? null;

            $existing = DB::table('countries')->where('code', $code)->first();
            if ($existing) {
                $countryMap[$code] = $existing->id;
                continue;
            }

            $id = DB::table('countries')->insertGetId([
                'name' => $name,
                'code' => $code,
                'phone_code' => $phoneCode ?: null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $countryMap[$code] = $id;
            $countryCount++;
        }

        $this->info('✅ ' . $countryCount . ' countries processed/imported.');

        // ============================================
        // 2. STATES IMPORT
        // ============================================
        $this->info('📥 Fetching states for selected countries...');
        $stateCount = 0;

        foreach ($countryMap as $code => $countryId) {
            $this->info("  → States for {$code}");

            try {
                $http = Http::timeout(30);
                if ($skipSSL) {
                    $http->withOptions(['verify' => false]);
                }

                $response = $http->withHeaders([
                    'X-CSCAPI-KEY' => $apiKey,
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'application/json',
                ])->get("https://countrystatecity.in/{$code}/states");

                $states = $response->json();

                if (is_array($states) && !empty($states)) {
                    foreach ($states as $state) {
                        if (!is_array($state))
                            continue;

                        $stateName = $state['name'] ?? null;
                        $stateCode = $state['iso2'] ?? null;
                        if (!$stateName)
                            continue;

                        $existing = DB::table('states')
                            ->where('code', $stateCode)
                            ->where('country_id', $countryId)
                            ->first();

                        if (!$existing) {
                            DB::table('states')->insert([
                                'country_id' => $countryId,
                                'name' => $stateName,
                                'code' => $stateCode,
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $stateCount++;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->warn("    ⚠️ Could not fetch states for {$code}: " . $e->getMessage());
            }
        }

        $this->info('✅ ' . $stateCount . ' states imported.');

        // ============================================
        // 3. CITIES IMPORT
        // ============================================
        $this->info('📥 Fetching cities for selected countries...');
        $cityCount = 0;

        foreach ($countryMap as $code => $countryId) {
            $this->info("  → Cities for {$code}");

            try {
                $http = Http::timeout(60);
                if ($skipSSL) {
                    $http->withOptions(['verify' => false]);
                }

                $response = $http->withHeaders([
                    'X-CSCAPI-KEY' => $apiKey,
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'application/json',
                ])->get("https://countrystatecity.in/{$code}/cities");

                $cities = $response->json();

                if (is_array($cities) && !empty($cities)) {
                    foreach ($cities as $city) {
                        if (!is_array($city))
                            continue;

                        $cityName = $city['name'] ?? null;
                        if (!$cityName)
                            continue;

                        $stateCode = $city['state_code'] ?? null;
                        $stateId = null;

                        if ($stateCode) {
                            $state = DB::table('states')
                                ->where('code', $stateCode)
                                ->where('country_id', $countryId)
                                ->first();
                            if ($state) {
                                $stateId = $state->id;
                            }
                        }

                        $existing = DB::table('cities')
                            ->where('name', $cityName)
                            ->where('country_id', $countryId)
                            ->first();

                        if (!$existing) {
                            DB::table('cities')->insert([
                                'country_id' => $countryId,
                                'state_id' => $stateId,
                                'name' => $cityName,
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $cityCount++;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->warn("    ⚠️ Could not fetch cities for {$code}: " . $e->getMessage());
            }
        }

        $this->info('🎉 SUCCESS! ' . $cityCount . ' cities imported successfully.');
    }
}
