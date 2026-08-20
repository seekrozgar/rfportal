<?php
// app/Http/Controllers/Admin/PackageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $type = request('type', 'all');

        $packages = Package::when($type !== 'all', function($query) use ($type) {
            return $query->where('type', $type);
        })
        ->orderBy('display_order')
        ->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    // ✅ NEW: Show single package (for view modal)
    public function show(Package $package)
    {
        return response()->json([
            'success' => true,
            'data' => $package
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:packages',
                'type' => 'required|in:employer,seeker',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'job_posts_limit' => 'nullable|integer|min:0',
                'resume_views_limit' => 'nullable|integer|min:0',
                'features' => 'nullable|array',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'badge_color' => 'nullable|string',
                'display_order' => 'nullable|integer',
                'description' => 'nullable|string',
            ]);

            $validated['features'] = $request->features ?? [];
            $validated['slug'] = Str::slug($request->name);

            $package = Package::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Package created successfully!',
                'data' => $package
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, Package $package)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:packages,name,' . $package->id,
                'type' => 'required|in:employer,seeker',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'job_posts_limit' => 'nullable|integer|min:0',
                'resume_views_limit' => 'nullable|integer|min:0',
                'features' => 'nullable|array',
                'is_featured' => 'boolean',
                'is_active' => 'boolean',
                'badge_color' => 'nullable|string',
                'display_order' => 'nullable|integer',
                'description' => 'nullable|string',
            ]);

            $validated['features'] = $request->features ?? [];
            $package->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Package updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Package $package)
    {
        try {
            if ($package->subscriptions()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete! This package has ' . $package->subscriptions()->count() . ' subscription(s).'
                ], 422);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(Package $package)
    {
        try {
            $package->update(['is_active' => !$package->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
