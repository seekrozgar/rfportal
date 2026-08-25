<?php
// app/Services/JobScrapingService.php

namespace App\Services;

use App\Models\JobPosting;
use App\Models\JobCategory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class JobScrapingService
{
    protected $sources = [
        // ✅ Working RSS Feeds
        'propakistani' => [
            'url' => 'https://propakistani.pk/edunation/scholarships/feed/',
            'enabled' => true,
            'name' => 'Pro Pakistani Jobs',
            'type' => 'rss',
            'timeout' => 30,
            'auto_publish' => false,
        ],
        'myjobsfeed' => [
            'url' => 'https://rss.app/feeds/m23r3SoiW3ls0lDN.xml',
            'enabled' => true,
            'name' => 'My Jobs Feed',
            'type' => 'rss',
            'timeout' => 30,
            'auto_publish' => false,
        ],
        'jobtoday' => [
            'url' => 'https://www.jobtoday.pk/feed/',
            'enabled' => true,
            'name' => 'JobToday Pakistan',
            'type' => 'rss',
            'timeout' => 30,
            'auto_publish' => false,
        ],
        'pakistanjobs' => [
            'url' => 'https://www.pakistanjobs.pk/feed/',
            'enabled' => false, // Disabled - 404
            'name' => 'Pakistan Jobs',
            'type' => 'rss',
            'timeout' => 30,
            'auto_publish' => false,
        ],
    ];

    /**
     * ✅ Get available sources
     */
    public function getSources(): array
    {
        $available = [];
        foreach ($this->sources as $key => $source) {
            if ($source['enabled']) {
                $available[$key] = $source['name'];
            }
        }
        return $available;
    }

    /**
     * ✅ Scrape jobs with auto-publish control
     */
    public function scrapeJobs(string $source, string $keywords = '', int $limit = 20, $categoryId = null, bool $autoPublish = false): array
    {
        if (!isset($this->sources[$source]) || !$this->sources[$source]['enabled']) {
            throw new \Exception('Source not available or disabled.');
        }

        $sourceConfig = $this->sources[$source];
        $added = 0;
        $skipped = 0;
        $errors = 0;

        Log::info("🌐 Scraping jobs from {$source}", ['type' => $sourceConfig['type'], 'auto_publish' => $autoPublish]);

        try {
            if ($sourceConfig['type'] === 'rss') {
                $result = $this->scrapeRss($sourceConfig, $source, $keywords, $limit, $categoryId, $autoPublish);
            } else {
                $result = $this->scrapeApi($sourceConfig, $source, $keywords, $limit, $categoryId, $autoPublish);
            }

            $added = $result['added'] ?? 0;
            $skipped = $result['skipped'] ?? 0;
            $errors = $result['errors'] ?? 0;

        } catch (\Exception $e) {
            $errors++;
            Log::error("💥 Critical error scraping {$source}", [
                'error' => $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ]);
        }

        return ['added' => $added, 'skipped' => $skipped, 'errors' => $errors];
    }

    /**
     * ✅ Scrape RSS feeds with auto-publish control
     */
    protected function scrapeRss(array $sourceConfig, string $sourceKey, string $keywords, int $limit, $categoryId, bool $autoPublish): array
    {
        $url = $sourceConfig['url'];
        $added = 0;
        $skipped = 0;
        $errors = 0;

        try {
            // ✅ Try to fetch RSS
            $xml = $this->fetchRssWithFallback($url, $sourceConfig['timeout'] ?? 30);

            if (!$xml) {
                Log::error("❌ Failed to fetch RSS from {$sourceKey}");
                return ['added' => 0, 'skipped' => 0, 'errors' => 1];
            }

            // ✅ Check if items exist
            if (!isset($xml->channel->item)) {
                Log::warning("⚠️ No items found in RSS feed from {$sourceKey}");
                return ['added' => 0, 'skipped' => 0, 'errors' => 0];
            }

            $items = $xml->channel->item;
            $itemCount = 0;

            foreach ($items as $item) {
                if ($itemCount >= $limit)
                    break;

                try {
                    $title = trim((string) $item->title);
                    $description = trim((string) $item->description);
                    $link = trim((string) $item->link);
                    $pubDate = (string) $item->pubDate;

                    // ✅ Skip if title is empty
                    if (empty($title))
                        continue;

                    // ✅ Check if already exists
                    $existing = JobPosting::where('title', $title)
                        ->orWhere('source_url', $link)
                        ->first();

                    if ($existing) {
                        $skipped++;
                        continue;
                    }

                    // ✅ Extract additional info
                    $companyName = $this->extractCompanyName($title, $description);
                    $location = $this->extractLocation($title, $description);

                    // ✅ Parse date
                    $deadline = null;
                    if (!empty($pubDate)) {
                        try {
                            $deadline = Carbon::parse($pubDate)->addDays(30);
                        } catch (\Exception $e) {
                            // Use default deadline
                        }
                    }

                    // ✅ Create job with auto-publish control
                    $jobData = [
                        'title' => $title,
                        'slug' => Str::slug($title) . '-' . uniqid(),
                        'description' => $this->cleanDescription($description),
                        'category_id' => $categoryId,
                        'location' => $location,
                        'company_name' => $companyName,
                        'deadline' => $deadline,
                        'apply_link' => $link,
                        'source' => $sourceKey,
                        'source_url' => $link,
                        'is_verified' => true,
                        'published_at' => now(),
                        'posted_by' => auth()->id() ?? 1,
                        'job_source' => 'admin',
                        'is_active' => $autoPublish ? true : false, // ✅ Auto-publish control
                    ];

                    JobPosting::create($jobData);

                    $added++;
                    $itemCount++;

                } catch (\Exception $e) {
                    $errors++;
                    Log::error("❌ Error processing RSS item", [
                        'error' => $e->getMessage(),
                        'title' => $item->title ?? 'unknown'
                    ]);
                }
            }

            Log::info("✅ RSS scraping completed", [
                'source' => $sourceKey,
                'added' => $added,
                'skipped' => $skipped,
                'errors' => $errors,
                'auto_publish' => $autoPublish
            ]);

            return ['added' => $added, 'skipped' => $skipped, 'errors' => $errors];

        } catch (\Exception $e) {
            Log::error("💥 RSS scraping error", ['error' => $e->getMessage()]);
            return ['added' => 0, 'skipped' => 0, 'errors' => 1];
        }
    }

    /**
     * ✅ Fetch RSS with multiple fallback methods
     */
    protected function fetchRssWithFallback(string $url, int $timeout = 30)
    {
        // ✅ Method 1: Laravel HTTP
        try {
            $response = Http::timeout($timeout)
                ->retry(2, 2000)
                ->withOptions([
                    'verify' => app()->environment('local') ? false : true,
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        'Accept' => 'application/rss+xml, application/xml, text/xml',
                    ],
                ])
                ->get($url);

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                if ($xml !== false) {
                    return $xml;
                }
            }
            Log::warning("⚠️ HTTP method failed", ['url' => $url, 'status' => $response->status()]);
        } catch (\Exception $e) {
            Log::warning("⚠️ HTTP method error", ['error' => $e->getMessage()]);
        }

        // ✅ Method 2: file_get_contents
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => $timeout,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'header' => 'Accept: application/rss+xml, application/xml, text/xml',
                ],
                'ssl' => [
                    'verify_peer' => !app()->environment('local'),
                    'verify_peer_name' => !app()->environment('local'),
                    'allow_self_signed' => app()->environment('local'),
                ],
            ]);

            $content = file_get_contents($url, false, $context);
            if ($content !== false) {
                $xml = simplexml_load_string($content);
                if ($xml !== false) {
                    return $xml;
                }
            }
            Log::warning("⚠️ file_get_contents method failed", ['url' => $url]);
        } catch (\Exception $e) {
            Log::warning("⚠️ file_get_contents error", ['error' => $e->getMessage()]);
        }

        // ✅ Method 3: cURL
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_SSL_VERIFYPEER => !app()->environment('local'),
                CURLOPT_SSL_VERIFYHOST => !app()->environment('local'),
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                CURLOPT_HTTPHEADER => ['Accept: application/rss+xml, application/xml, text/xml'],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
            ]);

            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($content !== false && $httpCode === 200) {
                $xml = simplexml_load_string($content);
                if ($xml !== false) {
                    return $xml;
                }
            }
            Log::warning("⚠️ cURL method failed", ['url' => $url, 'http_code' => $httpCode]);
        } catch (\Exception $e) {
            Log::warning("⚠️ cURL error", ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * ✅ Extract company name
     */
    protected function extractCompanyName(string $title, string $description): ?string
    {
        $patterns = [
            '/at\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i',
            '/in\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i',
            '/with\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i',
            '/Company:\s*([^<>\n]+)/i',
            '/Employer:\s*([^<>\n]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $description, $matches) || preg_match($pattern, $title, $matches)) {
                $name = trim($matches[1]);
                if (strlen($name) > 2 && strlen($name) < 100) {
                    return $name;
                }
            }
        }

        return 'Unknown Company';
    }

    /**
     * ✅ Extract location
     */
    protected function extractLocation(string $title, string $description): ?string
    {
        $locations = [
            'Lahore',
            'Karachi',
            'Islamabad',
            'Rawalpindi',
            'Peshawar',
            'Quetta',
            'Multan',
            'Faisalabad',
            'Hyderabad',
            'Gujranwala',
            'Sialkot',
            'Sargodha',
            'Bahawalpur',
            'Sukkur',
            'Larkana'
        ];

        foreach ($locations as $location) {
            if (stripos($description, $location) !== false || stripos($title, $location) !== false) {
                return $location;
            }
        }

        return 'Pakistan';
    }

    /**
     * ✅ Clean description
     */
    protected function cleanDescription(string $description): string
    {
        $description = strip_tags($description);
        $description = preg_replace('/Read\s+more.*$/i', '', $description);
        $description = preg_replace('/Continue\s+reading.*$/i', '', $description);
        $description = preg_replace('/\s+/', ' ', $description);
        return trim($description);
    }

    /**
     * ✅ API scraping (fallback)
     */
    protected function scrapeApi(array $sourceConfig, string $sourceKey, string $keywords, int $limit, $categoryId, bool $autoPublish): array
    {
        Log::info("📡 API scraping for {$sourceKey} is not implemented yet");
        return ['added' => 0, 'skipped' => 0, 'errors' => 1];
    }

    /**
     * ✅ Test connection
     */
    public function testConnection(string $source): array
    {
        if (!isset($this->sources[$source]) || !$this->sources[$source]['enabled']) {
            return ['success' => false, 'message' => 'Source not available'];
        }

        try {
            $url = $this->sources[$source]['url'];
            $response = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->head($url);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Connection successful'];
            }

            return ['success' => false, 'message' => 'HTTP ' . $response->status()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
