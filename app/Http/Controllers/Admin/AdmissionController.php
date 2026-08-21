<?php
// app/Http/Controllers/Admin/AdmissionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalAdmissions = Admission::count();
        $publishedCount = Admission::where('is_published', true)->count();
        $upcomingCount = Admission::where('last_date', '>=', now())->count();
        $expiredCount = Admission::where('last_date', '<', now())->count();

        return view('admin.admissions.index', compact(
            'admissions',
            'totalAdmissions',
            'publishedCount',
            'upcomingCount',
            'expiredCount'
        ));
    }

    public function create()
    {
        return view('admin.admissions.create');
    }

    public function store(Request $request)
    {
        try {
            // ✅ Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:admissions',
                'description' => 'required|string',
                'institution' => 'required|string|max:255',
                'programs_offered' => 'nullable|string',
                'category' => 'nullable|string|max:255',
                'last_date' => 'nullable|date|after_or_equal:today',
                'announcement_date' => 'nullable|date',
                'fee' => 'nullable|string|max:255',
                'apply_through' => 'nullable|string|max:255',
                'apply_link' => 'nullable|url|max:255',
                'eligibility' => 'nullable|string',
                'required_documents' => 'nullable|string',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:255',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_published' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['posted_by'] = Auth::id();
            $validated['is_published'] = $request->has('is_published');

            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $path = $file->store('admissions/images', 'public');
                $validated['featured_image'] = $path;
                $validated['featured_image_original'] = $file->getClientOriginalName();
            }

            Admission::create($validated);

            return redirect()->route('admin.admissions.index')
                ->with('success', 'Admission created successfully!');

        } catch (ValidationException $e) {
            // ✅ Validation errors - return with input
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // ✅ Other errors
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit(Admission $admission)
    {
        return view('admin.admissions.edit', compact('admission'));
    }

    public function update(Request $request, Admission $admission)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:admissions,title,' . $admission->id,
                'description' => 'required|string',
                'institution' => 'required|string|max:255',
                'programs_offered' => 'nullable|string',
                'category' => 'nullable|string|max:255',
                'last_date' => 'nullable|date|after_or_equal:today',
                'announcement_date' => 'nullable|date',
                'fee' => 'nullable|string|max:255',
                'apply_through' => 'nullable|string|max:255',
                'apply_link' => 'nullable|url|max:255',
                'eligibility' => 'nullable|string',
                'required_documents' => 'nullable|string',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:255',
                'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_published' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($request->title);
            $validated['is_published'] = $request->has('is_published');

            if ($request->hasFile('featured_image')) {
                if ($admission->featured_image) {
                    Storage::disk('public')->delete($admission->featured_image);
                }

                $file = $request->file('featured_image');
                $path = $file->store('admissions/images', 'public');
                $validated['featured_image'] = $path;
                $validated['featured_image_original'] = $file->getClientOriginalName();
            }

            $admission->update($validated);

            return redirect()->route('admin.admissions.index')
                ->with('success', 'Admission updated successfully!');

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

    public function destroy(Admission $admission)
    {
        try {
            if ($admission->featured_image) {
                Storage::disk('public')->delete($admission->featured_image);
            }

            $admission->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Admission deleted successfully!'
                ]);
            }

            return redirect()->route('admin.admissions.index')
                ->with('success', 'Admission deleted successfully!');
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

    public function toggleStatus(Admission $admission)
    {
        try {
            $admission->update(['is_published' => !$admission->is_published]);

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
