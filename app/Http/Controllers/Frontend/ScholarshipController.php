<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    /**
     * Display all scholarships
     */
    public function index(Request $request)
    {
        $query = Scholarship::where('is_published', true)
            ->where('is_draft', false);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%")
                  ->orWhere('university', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('scholarship_type', $request->type);
        }

        // Filter by degree level
        if ($request->filled('degree')) {
            $query->where('degree_level', $request->degree);
        }

        $scholarships = $query->latest()->paginate(12);

        // Stats
        $activeCount = Scholarship::where('is_published', true)
            ->where('is_draft', false)
            ->where(function($q) {
                $q->where('deadline', '>=', now())
                  ->orWhereNull('deadline');
            })->count();

        $fullyFundedCount = Scholarship::where('is_published', true)
            ->where('is_draft', false)
            ->where('scholarship_type', 'Fully Funded')
            ->count();

        return view('frontend.scholarships.index', compact('scholarships', 'activeCount', 'fullyFundedCount'));
    }

    /**
     * Show single scholarship details
     */
    public function show($slug)
    {
        $scholarship = Scholarship::where('slug', $slug)
            ->where('is_published', true)
            ->where('is_draft', false)
            ->firstOrFail();

        // Increment views
        $scholarship->increment('views_count');

        // Related scholarships (same degree level or type)
        $related = Scholarship::where('is_published', true)
            ->where('is_draft', false)
            ->where('id', '!=', $scholarship->id)
            ->where(function($q) use ($scholarship) {
                $q->where('degree_level', $scholarship->degree_level)
                  ->orWhere('scholarship_type', $scholarship->scholarship_type)
                  ->orWhere('country', $scholarship->country);
            })
            ->limit(4)
            ->get();

        return view('frontend.scholarships.show', compact('scholarship', 'related'));
    }
}
