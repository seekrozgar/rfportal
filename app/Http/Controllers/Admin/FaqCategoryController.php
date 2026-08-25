<?php
// app/Http/Controllers/Admin/FaqCategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::withCount('faqs')
            ->ordered()
            ->paginate(20);

        $totalCategories = FaqCategory::count();
        $activeCategories = FaqCategory::where('is_active', true)->count();
        $inactiveCategories = FaqCategory::where('is_active', false)->count();

        return view('admin.faqs.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories'
        ));
    }

    public function create()
    {
        return view('admin.faqs.categories.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:faq_categories',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ]);

            $validated['slug'] = Str::slug($request->name);
            $validated['is_active'] = $request->has('is_active');
            $validated['order'] = $request->order ?? 0;

            $category = FaqCategory::create($validated);

            Log::info('✅ FAQ Category created', ['id' => $category->id, 'name' => $category->name]);

            return redirect()->route('admin.faq-categories.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Category "' . $category->name . '" created successfully!'
                ]);

        } catch (\Exception $e) {
            Log::error('❌ Category creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function edit(FaqCategory $faqCategory)
    {
        return view('admin.faqs.categories.edit', compact('faqCategory'));
    }

    public function update(Request $request, FaqCategory $faqCategory)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:faq_categories,name,' . $faqCategory->id,
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:100',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
            ]);

            $validated['slug'] = Str::slug($request->name);
            $validated['is_active'] = $request->has('is_active');
            $validated['order'] = $request->order ?? 0;

            $faqCategory->update($validated);

            Log::info('✅ FAQ Category updated', ['id' => $faqCategory->id, 'name' => $faqCategory->name]);

            return redirect()->route('admin.faq-categories.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Category "' . $faqCategory->name . '" updated successfully!'
                ]);

        } catch (\Exception $e) {
            Log::error('❌ Category update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy(FaqCategory $faqCategory)
    {
        try {
            // ✅ Check if category has FAQs
            if ($faqCategory->faqs()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with FAQs. Please reassign or delete FAQs first.'
                ], 422);
            }

            $faqCategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category "' . $faqCategory->name . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(FaqCategory $faqCategory)
    {
        try {
            $newStatus = !$faqCategory->is_active;
            $faqCategory->update(['is_active' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Category "' . $faqCategory->name . '" is now ' . ($newStatus ? 'Active' : 'Inactive') . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    /**
     * ✅ Bulk action on categories
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:faq_categories,id',
                'action' => 'required|string|in:delete,activate,deactivate',
            ]);

            $ids = $request->ids;
            $action = $request->action;
            $updated = 0;

            foreach ($ids as $id) {
                $category = FaqCategory::find($id);
                if (!$category)
                    continue;

                switch ($action) {
                    case 'delete':
                        // ✅ Check if category has FAQs
                        if ($category->faqs()->count() > 0) {
                            continue; // Skip categories with FAQs
                        }
                        $category->delete();
                        $updated++;
                        break;
                    case 'activate':
                        $category->is_active = true;
                        $category->save();
                        $updated++;
                        break;
                    case 'deactivate':
                        $category->is_active = false;
                        $category->save();
                        $updated++;
                        break;
                }
            }

            $message = "{$updated} category(s) ";
            $message .= $action === 'delete' ? 'deleted' :
                ($action === 'activate' ? 'activated' : 'deactivated');
            $message .= ' successfully!';

            if ($action === 'delete' && $updated < count($ids)) {
                $message .= ' Categories with FAQs cannot be deleted.';
            }

            Log::info('✅ Bulk action performed on FAQ categories', ['action' => $action, 'count' => $updated]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Bulk action failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

}
