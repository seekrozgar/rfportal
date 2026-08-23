<?php
// app/Http/Controllers/Admin/JobCategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class JobCategoryController extends Controller
{
    /**
     * ✅ Display a listing of categories
     */
    public function index()
    {
        $categories = JobCategory::with(['parent', 'children'])
            ->orderBy('parent_id')
            ->orderBy('order')
            ->orderBy('name')
            ->paginate(20);

        $totalCategories = JobCategory::count();
        $activeCategories = JobCategory::where('is_active', true)->count();
        $inactiveCategories = JobCategory::where('is_active', false)->count();
        $rootCategories = JobCategory::whereNull('parent_id')->count();

        return view('admin.job-categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories',
            'rootCategories'
        ));
    }

    /**
     * ✅ Show the form for creating a new category
     */
    public function create()
    {
        $parents = JobCategory::root()->ordered()->get();
        return view('admin.job-categories.create', compact('parents'));
    }

    /**
     * ✅ Store a newly created category
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:job_categories',
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:job_categories,id',
                'icon' => 'nullable|string|max:100',
                'is_active' => 'nullable|boolean',
                'order' => 'nullable|integer|min:0',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'keywords' => 'nullable|string|max:255',
            ]);

            // ✅ Fix: Properly handle boolean value from checkbox
            $validated['slug'] = Str::slug($request->name);
            $validated['is_active'] = $request->has('is_active') && $request->input('is_active') == '1';
            $validated['order'] = $request->order ?? 0;

            // ✅ Handle featured image
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $path = $file->store('job-categories', 'public');
                $validated['featured_image'] = $path;
            }

            $category = JobCategory::create($validated);

            Log::info('✅ Job category created', ['id' => $category->id, 'name' => $category->name]);

            return redirect()->route('admin.job-categories.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Category "' . $category->name . '" created successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('❌ Category creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * ✅ Show the form for editing a category
     */
    public function edit(JobCategory $jobCategory)
    {
        $parents = JobCategory::where('id', '!=', $jobCategory->id)
            ->root()
            ->ordered()
            ->get();

        return view('admin.job-categories.edit', compact('jobCategory', 'parents'));
    }

    /**
     * ✅ Update the specified category
     */
    public function update(Request $request, JobCategory $jobCategory)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:job_categories,name,' . $jobCategory->id,
                'description' => 'nullable|string',
                'parent_id' => 'nullable|exists:job_categories,id',
                'icon' => 'nullable|string|max:100',
                'is_active' => 'nullable|boolean',
                'order' => 'nullable|integer|min:0',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'keywords' => 'nullable|string|max:255',
            ]);

            // ✅ Prevent category from being its own parent
            if (isset($validated['parent_id']) && $validated['parent_id'] == $jobCategory->id) {
                return redirect()->back()
                    ->withInput()
                    ->with('toast', [
                        'type' => 'error',
                        'message' => 'A category cannot be its own parent.'
                    ]);
            }

            // ✅ Fix: Properly handle boolean value
            $validated['slug'] = Str::slug($request->name);
            $validated['is_active'] = $request->has('is_active') && $request->input('is_active') == '1';
            $validated['order'] = $request->order ?? 0;

            // ✅ Handle featured image
            if ($request->hasFile('featured_image')) {
                if ($jobCategory->featured_image) {
                    Storage::disk('public')->delete($jobCategory->featured_image);
                }
                $file = $request->file('featured_image');
                $path = $file->store('job-categories', 'public');
                $validated['featured_image'] = $path;
            }

            $jobCategory->update($validated);

            Log::info('✅ Job category updated', ['id' => $jobCategory->id, 'name' => $jobCategory->name]);

            return redirect()->route('admin.job-categories.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Category "' . $jobCategory->name . '" updated successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('❌ Category update failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * ✅ Remove the specified category
     */
    public function destroy(JobCategory $jobCategory)
    {
        try {
            // ✅ Check if category has children
            if ($jobCategory->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with sub-categories. Please delete child categories first.'
                ], 422);
            }

            // ✅ Check if category has jobs
            if ($jobCategory->jobs()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with jobs. Please reassign or delete jobs first.'
                ], 422);
            }

            if ($jobCategory->featured_image) {
                Storage::disk('public')->delete($jobCategory->featured_image);
            }

            $jobCategory->delete();

            Log::info('✅ Job category deleted', ['id' => $jobCategory->id, 'name' => $jobCategory->name]);

            return response()->json([
                'success' => true,
                'message' => 'Category "' . $jobCategory->name . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Category deletion failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Toggle category status
     */
    public function toggleStatus(JobCategory $jobCategory)
    {
        try {
            $newStatus = !$jobCategory->is_active;
            $jobCategory->update(['is_active' => $newStatus]);

            Log::info('✅ Category status toggled', [
                'id' => $jobCategory->id,
                'is_active' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category "' . $jobCategory->name . '" is now ' . ($newStatus ? 'Active' : 'Inactive') . '!',
                'status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Reorder categories
     */
    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'order' => 'required|array',
                'order.*' => 'required|integer|exists:job_categories,id',
            ]);

            foreach ($request->order as $index => $id) {
                JobCategory::where('id', $id)->update(['order' => $index]);
            }

            Log::info('✅ Categories reordered');

            return response()->json([
                'success' => true,
                'message' => 'Categories reordered successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Bulk delete categories
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:job_categories,id',
            ]);

            $deleted = 0;
            $failed = 0;

            foreach ($request->ids as $id) {
                $category = JobCategory::find($id);
                if ($category) {
                    if ($category->children()->count() === 0 && $category->jobs()->count() === 0) {
                        if ($category->featured_image) {
                            Storage::disk('public')->delete($category->featured_image);
                        }
                        $category->delete();
                        $deleted++;
                    } else {
                        $failed++;
                    }
                }
            }

            Log::info('✅ Bulk categories deleted', ['deleted' => $deleted, 'failed' => $failed]);

            $message = $deleted . ' categories deleted successfully!';
            if ($failed > 0) {
                $message .= ' ' . $failed . ' categories could not be deleted (have sub-categories or jobs).';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'deleted' => $deleted,
                'failed' => $failed
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Get categories for AJAX select
     */
    public function getCategories(Request $request)
    {
        $search = $request->input('q');

        $categories = JobCategory::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })
            ->active()
            ->ordered()
            ->limit(20)
            ->get(['id', 'name', 'parent_id']);

        $formatted = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'text' => $category->name,
                'parent_id' => $category->parent_id,
            ];
        });

        return response()->json($formatted);
    }
    /**
     * ✅ Remove featured image
     */
    public function removeImage(JobCategory $jobCategory)
    {
        try {
            if ($jobCategory->featured_image) {
                Storage::disk('public')->delete($jobCategory->featured_image);
                $jobCategory->update(['featured_image' => null]);

                return response()->json([
                    'success' => true,
                    'message' => 'Image removed successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No image to remove.'
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }
}
