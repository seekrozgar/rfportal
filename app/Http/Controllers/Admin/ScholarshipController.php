<?php
// app/Http/Controllers/Admin/ScholarshipController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\User;
use App\Notifications\NewScholarshipNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ScholarshipsImport;
use App\Exports\ScholarshipsExport;
use Illuminate\Http\Client\ConnectionException;

class ScholarshipController extends Controller
{
    /**
     * ✅ RSS Sources Configuration
     */
    protected $rssSources = [
        'propakistani' => [
            'url' => 'https://propakistani.pk/edunation/scholarships/feed/',
            'name' => 'Pro Pakistani',
            'enabled' => true,
            'timeout' => 30,
        ],
        'scholars4dev' => [
            'url' => 'https://www.scholars4dev.com/feed/',
            'name' => 'Scholars4Dev',
            'enabled' => true,
            'timeout' => 30,
        ],
        'opportunitydesk' => [
            'url' => 'https://opportunitydesk.org/category/scholarships/feed/',
            'name' => 'Opportunity Desk',
            'enabled' => true,
            'timeout' => 30,
        ],
        'scholarshipscorner' => [
            'url' => 'https://scholarshipscorner.website/feed/',
            'name' => 'Scholarships Corner',
            'enabled' => true,
            'timeout' => 60,
        ],
        'studyportals' => [
            'url' => 'https://www.studyportals.com/scholarships/feed/',
            'name' => 'Study Portals',
            'enabled' => true, // Disabled by default
            'timeout' => 60,
        ],
        'scholarshipy' => [
            'url' => 'https://scholarshipy.com/feed/',
            'name' => 'Scholarshipy',
            'enabled' => true, // Test first
            'timeout' => 30,
        ],
    ];

    public function index()
    {
        $scholarships = Scholarship::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalScholarships = Scholarship::count();
        $publishedCount = Scholarship::where('is_published', true)->count();
        $upcomingCount = Scholarship::where('deadline', '>=', now())->count();
        $expiredCount = Scholarship::where('deadline', '<', now())->count();
        $draftCount = Scholarship::where('is_draft', true)->count();

        return view('admin.scholarships.index', compact(
            'scholarships',
            'totalScholarships',
            'publishedCount',
            'upcomingCount',
            'expiredCount',
            'draftCount'
        ));
    }

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:scholarships',
                'description' => 'nullable|string',
                'provider' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'university' => 'nullable|string|max:255',
                'degree_level' => 'nullable|string|max:255',
                'scholarship_type' => 'nullable|string|max:255',
                'amount' => 'nullable|string|max:255',
                'deadline' => 'nullable|date',
                'apply_link' => 'nullable|url|max:255',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'eligibility' => 'nullable|string',
                'benefits' => 'nullable|string',
                'required_documents' => 'nullable|string',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:255',
                'source_url' => 'nullable|url|max:255',
                'source' => 'nullable|string|max:255',
                'is_published' => 'boolean',
                'is_draft' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['posted_by'] = Auth::id();
            $validated['is_published'] = $request->has('is_published');
            $validated['is_draft'] = $request->has('is_draft');

            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $path = $file->store('scholarships/images', 'public');
                $validated['featured_image'] = $path;
                $validated['featured_image_original'] = $file->getClientOriginalName();
            }

            $scholarship = Scholarship::create($validated);

            // ✅ Send notification if published
            if ($scholarship->is_published) {
                $this->sendScholarshipNotifications([$scholarship]);
            }

            return redirect()->route('admin.scholarships.index')
                ->with('success', 'Scholarship created successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Scholarship $scholarship)
    {
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:scholarships,title,' . $scholarship->id,
                'description' => 'nullable|string',
                'provider' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'university' => 'nullable|string|max:255',
                'degree_level' => 'nullable|string|max:255',
                'scholarship_type' => 'nullable|string|max:255',
                'amount' => 'nullable|string|max:255',
                'deadline' => 'nullable|date',
                'apply_link' => 'nullable|url|max:255',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'eligibility' => 'nullable|string',
                'benefits' => 'nullable|string',
                'required_documents' => 'nullable|string',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:255',
                'source_url' => 'nullable|url|max:255',
                'source' => 'nullable|string|max:255',
                'status' => 'nullable|in:published,draft',  // ✅ FIX: Use status field
            ]);


            $validated['slug'] = Str::slug($request->title);

            // ✅ FIX: Handle status from radio button
    if ($request->has('status')) {
        if ($request->status === 'published') {
            $validated['is_published'] = true;
            $validated['is_draft'] = false;
        } else {
            $validated['is_published'] = false;
            $validated['is_draft'] = true;
        }
    }

            if ($request->hasFile('featured_image')) {
                if ($scholarship->featured_image) {
                    Storage::disk('public')->delete($scholarship->featured_image);
                }

                $file = $request->file('featured_image');
                $path = $file->store('scholarships/images', 'public');
                $validated['featured_image'] = $path;
                $validated['featured_image_original'] = $file->getClientOriginalName();
            }

            $wasPublished = $scholarship->is_published;
            $scholarship->update($validated);

            // ✅ Send notification if newly published
            if (!$wasPublished && $scholarship->is_published) {
                $this->sendScholarshipNotifications([$scholarship]);
            }

            return redirect()->route('admin.scholarships.index')
                ->with('success', 'Scholarship updated successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Scholarship $scholarship)
    {
        try {
            if ($scholarship->featured_image) {
                Storage::disk('public')->delete($scholarship->featured_image);
            }

            $scholarship->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Scholarship deleted successfully!'
                ]);
            }

            return redirect()->route('admin.scholarships.index')
                ->with('success', 'Scholarship deleted successfully!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
 * Toggle status (Publish/Unpublish)
 */
public function toggleStatus($id)
{
    $scholarship = Scholarship::findOrFail($id);

    // ✅ FIX: Toggle between published and draft
    if ($scholarship->is_published) {
        $scholarship->update([
            'is_published' => false,
            'is_draft' => true
        ]);
        $message = 'Scholarship unpublished successfully.';
    } else {
        $scholarship->update([
            'is_published' => true,
            'is_draft' => false
        ]);
        $message = 'Scholarship published successfully.';
    }

    return response()->json([
        'success' => true,
        'message' => $message
    ]);
}

    // ============================================================
    // ✅ RSS FEED SCRAPING WITH MULTIPLE SOURCES
    // ============================================================

    /**
     * ✅ Display RSS scraping view with source selection
     */
    public function showScrapeForm()
    {
        $sources = $this->rssSources;
        return view('admin.scholarships.scrape', compact('sources'));
    }

    /**
     * ✅ Scrape scholarships from selected RSS source
     */
    public function scrape(Request $request)
    {
        $request->validate([
            'source' => 'required|string|in:' . implode(',', array_keys($this->rssSources)),
        ]);

        $sourceKey = $request->source;

        if (!isset($this->rssSources[$sourceKey])) {
            return back()->with('error', 'Invalid RSS source selected.');
        }

        $source = $this->rssSources[$sourceKey];

        if (!$source['enabled']) {
            return back()->with('error', 'This RSS source is currently disabled.');
        }

        Log::info('📡 RSS Scraping Started', [
            'source' => $sourceKey,
            'url' => $source['url'],
            'time' => now()
        ]);

        $result = $this->scrapeSingleSource($source, $sourceKey);

        $message = "✅ {$result['added']} new scholarships scraped from {$source['name']}!";
        if ($result['skipped'] > 0) {
            $message .= " ⏭️ {$result['skipped']} scholarships already exist and were skipped.";
        }
        if ($result['errors'] > 0) {
            $message .= " ⚠️ {$result['errors']} errors occurred. Check logs for details.";
        }

        return redirect()->route('admin.scholarships.index')
            ->with('success', $message);
    }

    /**
     * ✅ Scrape ALL enabled RSS sources
     */
    public function scrapeAll()
    {
        Log::info('📡 Multi-Source RSS Scraping Started', ['time' => now()]);

        $results = [];
        $totalAdded = 0;
        $totalSkipped = 0;
        $totalErrors = 0;
        $allScholarships = [];

        foreach ($this->rssSources as $key => $source) {
            if (!$source['enabled']) {
                Log::info("⏭️ Skipping disabled source: {$key}");
                continue;
            }

            $result = $this->scrapeSingleSource($source, $key);
            $results[$key] = $result;

            $totalAdded += $result['added'];
            $totalSkipped += $result['skipped'];
            $totalErrors += $result['errors'];

            // Collect new scholarships for notifications
            if (!empty($result['new_scholarships'])) {
                $allScholarships = array_merge($allScholarships, $result['new_scholarships']);
            }
        }

        Log::info('📊 Multi-Source Scraping Summary', [
            'sources_processed' => count($results),
            'total_added' => $totalAdded,
            'total_skipped' => $totalSkipped,
            'total_errors' => $totalErrors,
            'details' => $results
        ]);

        // ✅ Send notifications for all new scholarships
        if (!empty($allScholarships)) {
            $this->sendScholarshipNotifications($allScholarships);
        }

        $message = "✅ {$totalAdded} new scholarships scraped from " . count($results) . " sources!";
        if ($totalSkipped > 0) {
            $message .= " ⏭️ {$totalSkipped} already exist.";
        }
        if ($totalErrors > 0) {
            $message .= " ⚠️ {$totalErrors} errors occurred. Check logs for details.";
        }

        return redirect()->route('admin.scholarships.index')
            ->with('success', $message);
    }

    /**
     * ✅ Scrape Single RSS Source
     */
    /**
     * ✅ Scrape Single RSS Source - FIXED with better timeout handling
     */
    protected function scrapeSingleSource($source, $sourceKey)
    {
        $sourceName = $source['name'];
        $url = $source['url'];
        $timeout = $source['timeout'] ?? 30;

        Log::info("🌐 Fetching RSS from {$sourceName}", ['url' => $url, 'timeout' => $timeout]);

        $added = 0;
        $skipped = 0;
        $errors = 0;
        $newScholarships = [];

        try {
            // ✅ HTTP request with increased timeout and retry
            $response = Http::timeout($timeout)
                ->retry(2, 3000) // ✅ Retry 2 times with 3 second delay
                ->withOptions([
                    'verify' => false, // For development
                    'connect_timeout' => $timeout,
                    'read_timeout' => $timeout,
                ])
                ->get($url);

            Log::info('📊 RSS Response', [
                'source' => $sourceName,
                'status' => $response->status(),
            ]);

            if ($response->status() !== 200) {
                Log::error("❌ Failed to fetch RSS from {$sourceName}", [
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 200)
                ]);
                return ['added' => 0, 'skipped' => 0, 'errors' => 1, 'new_scholarships' => []];
            }

            // ✅ Parse XML
            $xml = simplexml_load_string($response->body());

            if ($xml === false) {
                Log::error("❌ Invalid XML from {$sourceName}", [
                    'errors' => libxml_get_errors()
                ]);
                return ['added' => 0, 'skipped' => 0, 'errors' => 1, 'new_scholarships' => []];
            }

            // ✅ Check if items exist
            if (!isset($xml->channel->item)) {
                Log::warning("⚠️ No items found in RSS feed from {$sourceName}");
                return ['added' => 0, 'skipped' => 0, 'errors' => 0, 'new_scholarships' => []];
            }

            $items = $xml->channel->item;
            Log::info("📰 {$sourceName} has " . count($items) . " items");

            // ✅ Limit items to prevent memory issues
            $maxItems = 50;
            $itemCount = 0;

            foreach ($items as $index => $item) {
                // ✅ Limit items
                $itemCount++;
                if ($itemCount > $maxItems) {
                    Log::info("⏭️ Reached max items limit ({$maxItems}) for {$sourceName}");
                    break;
                }

                try {
                    // ✅ Extract basic data
                    $title = trim((string) $item->title);
                    $link = trim((string) $item->link);

                    if (empty($title) || empty($link)) {
                        $skipped++;
                        continue;
                    }

                    // ✅ Check for duplicates
                    $existing = Scholarship::where('title', $title)
                        ->orWhere('source_url', $link)
                        ->first();

                    if ($existing) {
                        Log::debug('⏭️ Already exists', ['title' => $title, 'id' => $existing->id]);
                        $skipped++;
                        continue;
                    }

                    // ✅ Extract content based on source
                    $content = $this->getContentFromSource($item, $sourceKey);
                    $description = $this->cleanDescription($content);

                    // ✅ Extract all data
                    $extractedData = $this->extractAllData($content, $title, $sourceKey);

                    // ✅ Create scholarship
                    $scholarship = Scholarship::create([
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'description' => $description,
                        'provider' => $extractedData['provider'],
                        'university' => $extractedData['university'],
                        'country' => $extractedData['country'],
                        'amount' => $extractedData['amount'],
                        'deadline' => $extractedData['deadline'],
                        'apply_link' => $extractedData['apply_link'],
                        'source_url' => $link,
                        'source' => $sourceKey,
                        'degree_level' => $extractedData['degree_level'],
                        'scholarship_type' => $extractedData['scholarship_type'],
                        'posted_by' => Auth::id() ?? 1,
                        'is_published' => false,
                        'is_draft' => true,
                    ]);

                    $added++;
                    $newScholarships[] = $scholarship;
                    Log::debug("✅ Added scholarship from {$sourceName}", ['title' => $title]);

                } catch (\Exception $e) {
                    $errors++;
                    Log::error("❌ Error processing item from {$sourceName}", [
                        'index' => $index,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

        } catch (ConnectionException $e) {
            $errors++;
            Log::error("🔌 Connection error scraping {$sourceName}", [
                'error' => $e->getMessage(),
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            $errors++;
            Log::error("💥 Critical error scraping {$sourceName}", [
                'error' => $e->getMessage(),
            ]);
        }

        return [
            'added' => $added,
            'skipped' => $skipped,
            'errors' => $errors,
            'new_scholarships' => $newScholarships
        ];
    }

    /**
     * ✅ Get content from different sources
     */
    protected function getContentFromSource($item, $source)
    {
        switch ($source) {
            case 'propakistani':
                return (string) $item->children('content', true)->encoded;
            case 'scholars4dev':
                return (string) $item->description;
            case 'opportunitydesk':
                return (string) $item->children('content', true)->encoded ?: (string) $item->description;
            case 'scholarshipscorner':
                return (string) $item->children('content', true)->encoded ?: (string) $item->description;
            case 'studyportals':
                return (string) $item->description;
            default:
                return (string) $item->children('content', true)->encoded ?: (string) $item->description;
        }
    }

    /**
     * ✅ Extract all data with source-specific parsing
     */
    protected function extractAllData($content, $title, $source)
    {
        $data = [
            'provider' => null,
            'university' => null,
            'country' => null,
            'amount' => null,
            'deadline' => null,
            'apply_link' => null,
            'degree_level' => null,
            'scholarship_type' => null,
        ];

        switch ($source) {
            case 'propakistani':
                $data['provider'] = $this->extractProvider($content, $title);
                $data['university'] = $this->extractUniversity($content, $title);
                $data['country'] = $this->extractCountry($content, $title);
                $data['amount'] = $this->extractAmount($content, $title);
                $data['deadline'] = $this->extractDeadline($content, $title);
                $data['apply_link'] = $this->extractApplyLink($content);
                $data['degree_level'] = $this->extractDegreeLevel($content, $title);
                $data['scholarship_type'] = $this->extractScholarshipType($content, $title);
                break;

            case 'scholars4dev':
                $data['provider'] = $this->extractProviderScholars4dev($content, $title);
                $data['country'] = $this->extractCountryScholars4dev($content);
                $data['deadline'] = $this->extractDeadlineScholars4dev($content);
                $data['apply_link'] = $this->extractApplyLink($content);
                $data['degree_level'] = $this->extractDegreeLevel($content, $title);
                $data['scholarship_type'] = $this->extractScholarshipType($content, $title);
                break;

            case 'opportunitydesk':
                $data['provider'] = $this->extractProvider($content, $title);
                $data['country'] = $this->extractCountry($content, $title);
                $data['deadline'] = $this->extractDeadline($content, $title);
                $data['apply_link'] = $this->extractApplyLink($content);
                $data['degree_level'] = $this->extractDegreeLevel($content, $title);
                $data['scholarship_type'] = $this->extractScholarshipType($content, $title);
                break;

            default:
                // Generic extraction
                $data['provider'] = $this->extractProvider($content, $title);
                $data['university'] = $this->extractUniversity($content, $title);
                $data['country'] = $this->extractCountry($content, $title);
                $data['amount'] = $this->extractAmount($content, $title);
                $data['deadline'] = $this->extractDeadline($content, $title);
                $data['apply_link'] = $this->extractApplyLink($content);
                $data['degree_level'] = $this->extractDegreeLevel($content, $title);
                $data['scholarship_type'] = $this->extractScholarshipType($content, $title);
        }

        return $data;
    }

    // ✅ Source-specific extraction methods
    protected function extractProviderScholars4dev($content, $title)
    {
        if (preg_match('/Provider:?\s*([^<>\n]+)/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return $this->extractProvider($content, $title);
    }

    protected function extractCountryScholars4dev($content)
    {
        if (preg_match('/Country:?\s*([^<>\n]+)/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    protected function extractDeadlineScholars4dev($content)
    {
        if (preg_match('/Deadline:?\s*([^<>\n]+)/i', $content, $matches)) {
            $deadlineStr = trim($matches[1]);
            try {
                return Carbon::parse($deadlineStr)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    // ============================================================
    // ✅ EXTRACTION METHODS (Enhanced)
    // ============================================================

    protected function extractUniversity($content, $title)
    {
        $patterns = [
            '/University\s+of\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i',
            '/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)\s+University/i',
            '/at\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*\s+(?:University|College|Institute|School))/i',
            '/<a[^>]*>([^<]*(?:University|College|Institute|School)[^<]*)<\/a>/i',
            '/University[:\s]+([^<>\n]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                $uni = trim($matches[1]);
                if (strlen($uni) > 3 && !str_contains($uni, 'http')) {
                    return $uni;
                }
            }
            if (preg_match($pattern, $title, $matches)) {
                $uni = trim($matches[1]);
                if (strlen($uni) > 3 && !str_contains($uni, 'http')) {
                    return $uni;
                }
            }
        }

        return null;
    }

    protected function extractProvider($content, $title)
    {
        $patterns = [
            '/by\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)\s+(?:Scholarship|Program)/i',
            '/provided\s+by\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i',
            '/offered\s+by\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i',
            '/Provider[:\s]+([^<>\n]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                $provider = trim($matches[1]);
                if (strlen($provider) > 3 && !str_contains($provider, 'http')) {
                    return $provider;
                }
            }
            if (preg_match($pattern, $title, $matches)) {
                $provider = trim($matches[1]);
                if (strlen($provider) > 3 && !str_contains($provider, 'http')) {
                    return $provider;
                }
            }
        }

        return null;
    }

    protected function extractDeadline($content, $title)
    {
        $patterns = [
            '/(?:deadline|apply by|last date|closing date|Deadline)[:\s]*([A-Z][a-z]+\s+\d{1,2}(?:st|nd|rd|th)?,?\s+\d{4})/i',
            '/(\d{1,2}(?:st|nd|rd|th)?\s+[A-Z][a-z]+\s+\d{4})/',
            '/([A-Z][a-z]+\s+\d{1,2},?\s+\d{4})/',
            '/(\d{4}-\d{2}-\d{2})/',
            '/(\d{2}\/\d{2}\/\d{4})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                $dateStr = trim($matches[1]);
                try {
                    $date = date('Y-m-d', strtotime($dateStr));
                    if ($date && $date > '2000-01-01') {
                        return $date;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            if (preg_match($pattern, $title, $matches)) {
                $dateStr = trim($matches[1]);
                try {
                    $date = date('Y-m-d', strtotime($dateStr));
                    if ($date && $date > '2000-01-01') {
                        return $date;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return null;
    }

    protected function extractCountry($content, $title)
    {
        $countries = [
            'Pakistan',
            'USA',
            'UK',
            'Canada',
            'Australia',
            'Germany',
            'France',
            'Italy',
            'Spain',
            'China',
            'Japan',
            'South Korea',
            'Turkey',
            'UAE',
            'Saudi Arabia',
            'Malaysia',
            'Singapore',
            'Netherlands',
            'Switzerland',
            'Sweden',
            'Norway',
            'Denmark',
            'Finland',
            'Belgium',
            'Austria',
            'Ireland',
            'New Zealand',
            'South Africa',
            'Brazil',
            'Mexico',
            'Argentina',
            'Chile',
            'Colombia',
            'Peru',
            'Venezuela',
            'Egypt',
            'Nigeria',
            'Kenya',
            'Morocco',
            'India',
            'Bangladesh',
            'Sri Lanka',
            'Nepal',
            'Afghanistan',
            'Iran',
            'Iraq',
            'Jordan',
            'Lebanon',
            'Qatar',
            'Kuwait',
            'Oman',
            'Bahrain'
        ];

        foreach ($countries as $country) {
            if (stripos($content, $country) !== false || stripos($title, $country) !== false) {
                return $country;
            }
        }

        return null;
    }

    protected function extractAmount($content, $title)
    {
        $patterns = [
            '/(?:stipend|amount|scholarship|funding|Value)[:\s]*([A-Z]{3}\s*[\d,]+)/i',
            '/([A-Z]{3}\s*[\d,]+)\s*(?:per|annum|per annum|yearly|monthly)/i',
            '/\$\s*([\d,]+)/',
            '/AUD\s*([\d,]+)/',
            '/PKR\s*([\d,]+)/',
            '/EUR\s*([\d,]+)/',
            '/GBP\s*([\d,]+)/',
            '/\d{1,3}(?:,\d{3})*\s*(?:USD|EUR|GBP|AUD|PKR)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                return trim($matches[1]);
            }
            if (preg_match($pattern, $title, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    protected function extractDegreeLevel($content, $title)
    {
        $levels = [
            'Bachelor' => ['Bachelor', 'BSc', 'BA', 'BS', 'Undergraduate', 'Bachelors'],
            'Master' => ['Master', 'MSc', 'MA', 'MS', 'Graduate', 'Masters'],
            'M.Phil' => ['M.Phil', 'MPhil'],
            'PhD' => ['PhD', 'Doctorate', 'Doctoral', 'Ph.D', 'Doctor'],
            'Post Doc' => ['Post Doc', 'Postdoctoral', 'Post-doctoral'],
            'Diploma' => ['Diploma', 'Certificate'],
        ];

        foreach ($levels as $level => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($content, $keyword) !== false || stripos($title, $keyword) !== false) {
                    return $level;
                }
            }
        }

        return null;
    }

    protected function extractScholarshipType($content, $title)
    {
        if (stripos($content, 'Fully Funded') !== false || stripos($title, 'Fully Funded') !== false) {
            return 'Fully Funded';
        }
        if (stripos($content, 'Partial Funded') !== false || stripos($title, 'Partial Funded') !== false) {
            return 'Partial Funded';
        }
        if (stripos($content, 'Tuition Waiver') !== false || stripos($title, 'Tuition Waiver') !== false) {
            return 'Tuition Waiver';
        }
        if (stripos($content, 'Stipend') !== false || stripos($title, 'Stipend') !== false) {
            return 'Stipend';
        }
        if (stripos($content, 'Scholarship') !== false) {
            return 'Scholarship';
        }

        return null;
    }

    protected function extractApplyLink($content)
    {
        $patterns = [
            '/<a[^>]*href="([^"]*)"[^>]*>(?:[^<]*Apply[^<]*)<\/a>/i',
            '/<a[^>]*href="([^"]*apply[^"]*)"[^>]*>/i',
            '/apply\s+(?:now|here|online|for)\s*:?\s*([^\s<]+)/i',
            '/href="([^"]*)"[^>]*>Apply Now/i',
            '/Registration Link[:\s]*([^\s<]+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $matches)) {
                $link = trim($matches[1]);
                if (filter_var($link, FILTER_VALIDATE_URL)) {
                    return $link;
                }
            }
        }

        return null;
    }

    protected function cleanDescription($content)
    {
        // Remove "The post... appeared first on..." part
        $content = preg_replace('/<p>The post.*?appeared first on.*?<\/p>/s', '', $content);

        // Remove "Share this:" or similar
        $content = preg_replace('/<p>Share this:.*?<\/p>/s', '', $content);

        // Remove "Related Posts" section
        $content = preg_replace('/<div[^>]*class="[^"]*related[^"]*"[^>]*>.*?<\/div>/s', '', $content);

        // Strip tags but keep useful formatting
        $content = strip_tags($content, '<p><br><strong><em><ul><li><ol><h2><h3><h4><h5><a>');

        // Clean up extra whitespace
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);

        return $content;
    }

    // ============================================================
    // ✅ EMAIL NOTIFICATIONS
    // ============================================================

    /**
     * ✅ Send notifications for new scholarships
     */
    protected function sendScholarshipNotifications($scholarships)
    {
        if (empty($scholarships)) {
            return;
        }

        // ✅ Get users who should receive notifications
        $users = User::where(function ($query) {
            $query->where('role', 'admin')
                ->orWhere('role', 'employer')
                ->orWhere('notify_scholarships', true);
        })
            ->where('email_verified_at', '!=', null)
            ->get();

        if ($users->isEmpty()) {
            Log::info('No users to notify for new scholarships');
            return;
        }

        $newCount = count($scholarships);

        foreach ($users as $user) {
            try {
                // ✅ Send notification for each scholarship or batch
                foreach ($scholarships as $scholarship) {
                    $user->notify(new NewScholarshipNotification($scholarship, $newCount));
                }

                Log::info("📧 Sent scholarship notifications to user", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'count' => $newCount
                ]);
            } catch (\Exception $e) {
                Log::error("❌ Failed to send notification to user", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    // ============================================================
    // ✅ BULK IMPORT / EXPORT
    // ============================================================

    public function export()
    {
        return Excel::download(new ScholarshipsExport, 'scholarships_' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new ScholarshipsImport();
            Excel::import($import, $request->file('file'));

            $message = "✅ Scholarships imported successfully!";
            Log::info('📥 Scholarships imported', ['user' => auth()->id()]);

            return redirect()->route('admin.scholarships.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('❌ Import failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Title',
            'Description',
            'Provider',
            'University',
            'Country',
            'Amount',
            'Deadline (YYYY-MM-DD)',
            'Degree Level',
            'Scholarship Type',
            'Apply Link',
            'Status (draft/published)'
        ];

        $file = tempnam(sys_get_temp_dir(), 'scholarship_template');
        $handle = fopen($file, 'w');
        fputcsv($handle, $headers);
        fclose($handle);

        return response()->download($file, 'scholarship_template.csv', [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }
}
