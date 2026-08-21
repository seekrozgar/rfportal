<?php
// app/Http/Controllers/Admin/ResultController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.results.index', compact('results'));
    }

    public function create()
    {
        return view('admin.results.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:results',
                'description' => 'required|string',
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
                'institution' => 'nullable|string|max:255',
                'exam_type' => 'nullable|string|max:255',
                'result_date' => 'nullable|date',
                'category' => 'nullable|string|max:255',
                'is_published' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['posted_by'] = Auth::id();
            $validated['is_published'] = $request->has('is_published');

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('results/files', 'public');
                $validated['file_path'] = $path;
                $validated['file_original_name'] = $file->getClientOriginalName();
            }

            Result::create($validated);

            return redirect()->route('admin.results.index')
                ->with('success', 'Result created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Result $result)
    {
        return view('admin.results.edit', compact('result'));
    }

    public function update(Request $request, Result $result)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:results,title,' . $result->id,
                'description' => 'required|string',
                'file' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
                'institution' => 'nullable|string|max:255',
                'exam_type' => 'nullable|string|max:255',
                'result_date' => 'nullable|date',
                'category' => 'nullable|string|max:255',
                'is_published' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['is_published'] = $request->has('is_published');

            if ($request->hasFile('file')) {
                if ($result->file_path) {
                    Storage::disk('public')->delete($result->file_path);
                }

                $file = $request->file('file');
                $path = $file->store('results/files', 'public');
                $validated['file_path'] = $path;
                $validated['file_original_name'] = $file->getClientOriginalName();
            }

            $result->update($validated);

            return redirect()->route('admin.results.index')
                ->with('success', 'Result updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Result $result)
    {
        try {
            if ($result->file_path) {
                Storage::disk('public')->delete($result->file_path);
            }

            $result->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Result deleted successfully!'
                ]);
            }

            return redirect()->route('admin.results.index')
                ->with('success', 'Result deleted successfully!');
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

    public function toggleStatus(Result $result)
    {
        try {
            $result->update(['is_published' => !$result->is_published]);

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
