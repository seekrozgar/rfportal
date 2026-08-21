<?php
// app/Http/Controllers/Admin/NewsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:news',
                'content' => 'required|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'source' => 'nullable|string|max:255',
                'news_date' => 'nullable|date',
                'is_published' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['posted_by'] = Auth::id();
            $validated['is_published'] = $request->has('is_published');

            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $path = $file->store('news/images', 'public');
                $validated['featured_image'] = $path;
                $validated['featured_image_original'] = $file->getClientOriginalName();
            }

            News::create($validated);

            return redirect()->route('admin.news.index')
                ->with('success', 'News created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:news,title,' . $news->id,
                'content' => 'required|string',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'source' => 'nullable|string|max:255',
                'news_date' => 'nullable|date',
                'is_published' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['is_published'] = $request->has('is_published');

            if ($request->hasFile('featured_image')) {
                if ($news->featured_image) {
                    Storage::disk('public')->delete($news->featured_image);
                }

                $file = $request->file('featured_image');
                $path = $file->store('news/images', 'public');
                $validated['featured_image'] = $path;
                $validated['featured_image_original'] = $file->getClientOriginalName();
            }

            $news->update($validated);

            return redirect()->route('admin.news.index')
                ->with('success', 'News updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(News $news)
    {
        try {
            if ($news->featured_image) {
                Storage::disk('public')->delete($news->featured_image);
            }

            $news->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'News deleted successfully!'
                ]);
            }

            return redirect()->route('admin.news.index')
                ->with('success', 'News deleted successfully!');
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

    public function toggleStatus(News $news)
    {
        try {
            $news->update(['is_published' => !$news->is_published]);

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
