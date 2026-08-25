<?php
// app/Http/Controllers/Admin/FaqController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with(['category', 'creator'])
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalFaqs = Faq::count();
        $activeFaqs = Faq::where('is_active', true)->count();
        $inactiveFaqs = Faq::where('is_active', false)->count();
        $featuredFaqs = Faq::where('is_featured', true)->count();
        $totalCategories = FaqCategory::count();

        return view('admin.faqs.index', compact(
            'faqs',
            'totalFaqs',
            'activeFaqs',
            'inactiveFaqs',
            'featuredFaqs',
            'totalCategories'
        ));
    }

    public function create()
    {
        $categories = FaqCategory::active()->ordered()->get();
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:255|unique:faqs',
                'answer' => 'required|string',
                'category_id' => 'nullable|exists:faq_categories,id',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
            ]);

            $validated['slug'] = Str::slug($request->question);
            $validated['created_by'] = auth()->id();
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['order'] = $request->order ?? 0;

            $faq = Faq::create($validated);

            Log::info('✅ FAQ created', ['id' => $faq->id, 'question' => $faq->question]);

            return redirect()->route('admin.faqs.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'FAQ "' . $faq->question . '" created successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('❌ FAQ creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function edit(Faq $faq)
    {
        $categories = FaqCategory::active()->ordered()->get();
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:255|unique:faqs,question,' . $faq->id,
                'answer' => 'required|string',
                'category_id' => 'nullable|exists:faq_categories,id',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
            ]);

            $validated['slug'] = Str::slug($request->question);
            $validated['updated_by'] = auth()->id();
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['order'] = $request->order ?? 0;

            $faq->update($validated);

            Log::info('✅ FAQ updated', ['id' => $faq->id, 'question' => $faq->question]);

            return redirect()->route('admin.faqs.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'FAQ "' . $faq->question . '" updated successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('❌ FAQ update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();

            Log::info('✅ FAQ deleted', ['id' => $faq->id, 'question' => $faq->question]);

            return response()->json([
                'success' => true,
                'message' => 'FAQ "' . $faq->question . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ FAQ deletion failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(Faq $faq)
    {
        try {
            $newStatus = !$faq->is_active;
            $faq->update(['is_active' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'FAQ "' . $faq->question . '" is now ' . ($newStatus ? 'Active' : 'Inactive') . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    public function toggleFeatured(Faq $faq)
    {
        try {
            $newStatus = !$faq->is_featured;
            $faq->update(['is_featured' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'FAQ "' . $faq->question . '" is now ' . ($newStatus ? 'Featured' : 'Not Featured') . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'order' => 'required|array',
                'order.*' => 'required|integer|exists:faqs,id',
            ]);

            foreach ($request->order as $index => $id) {
                Faq::where('id', $id)->update(['order' => $index]);
            }

            Log::info('✅ FAQs reordered');

            return response()->json([
                'success' => true,
                'message' => 'FAQs reordered successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:faqs,id',
                'action' => 'required|string|in:delete,activate,deactivate,featured,unfeatured',
            ]);

            $ids = $request->ids;
            $action = $request->action;
            $updated = 0;

            foreach ($ids as $id) {
                $faq = Faq::find($id);
                if (!$faq)
                    continue;

                switch ($action) {
                    case 'delete':
                        $faq->delete();
                        $updated++;
                        break;
                    case 'activate':
                        $faq->is_active = true;
                        $faq->save();
                        $updated++;
                        break;
                    case 'deactivate':
                        $faq->is_active = false;
                        $faq->save();
                        $updated++;
                        break;
                    case 'featured':
                        $faq->is_featured = true;
                        $faq->save();
                        $updated++;
                        break;
                    case 'unfeatured':
                        $faq->is_featured = false;
                        $faq->save();
                        $updated++;
                        break;
                }
            }

            $message = "{$updated} FAQ(s) ";
            $message .= $action === 'delete' ? 'deleted' :
                ($action === 'activate' ? 'activated' :
                    ($action === 'deactivate' ? 'deactivated' :
                        ($action === 'featured' ? 'marked as featured' : 'removed from featured')));
            $message .= ' successfully!';

            Log::info('✅ Bulk action performed on FAQs', ['action' => $action, 'count' => $updated]);

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
