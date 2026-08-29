<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Company;

use App\Services\NotificationService;
use App\Notifications\CompanyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CompanyProfileController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $user->name . "'s Company",
                'is_active' => true,
            ]);

            $user->update(['company_id' => $company->id]);
        }

        return redirect()->route('employer.company-profile.edit');
    }

    public function edit()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => $user->name . "'s Company",
                'is_active' => true,
            ]);

            $user->update(['company_id' => $company->id]);
        }

        $industries = $this->getIndustries();
        $companySizes = $this->getCompanySizes();

        // ✅ Get profile completion percentage
        $completionPercentage = (int) ($company->completion_percentage ?? 0);

        return view('employer.company-profile.edit', compact(
            'company',
            'industries',
            'companySizes',
            'completionPercentage'
        ));
    }

    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            $company = $user->company;

            if (!$company) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'message' => 'Company not found. Please contact support.',
                ]);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'website' => 'nullable|url|max:255',
                'address' => 'nullable|string|max:500',
                'description' => 'nullable|string|max:2000',
                'industry' => 'nullable|string|max:255',
                'company_size' => 'nullable|string|max:50',
                'founded_year' => 'nullable|string|max:10',
                'headquarters' => 'nullable|string|max:255',
                'ntn_number' => 'nullable|string|max:50',
                'secp_number' => 'nullable|string|max:50',
                'facebook' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'business_license' => 'nullable|file|mimes:pdf,jpeg,png,jpg,webp|max:5120',
            ]);

            $this->storeUploadedFile($request, $company, $validated, 'logo', 'companies/logos');
            $this->storeUploadedFile($request, $company, $validated, 'cover_image', 'companies/covers');
            $this->storeUploadedFile($request, $company, $validated, 'business_license', 'companies/licenses');

            $company->update($validated);
            $company->refresh();

            if ($company->is_complete) {
                $user->update([
                    'is_company_profile_complete' => true,
                    'company_profile_completed_at' => $user->company_profile_completed_at ?: now(),
                ]);
            } else {
                $user->update([
                    'is_company_profile_complete' => false,
                    'company_profile_completed_at' => null,
                ]);
            }

            Log::info('Company profile updated', [
                'company_id' => $company->id,
                'user_id' => $user->id,
            ]);

            return redirect()->route('employer.company-profile.edit')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Company profile updated successfully!',
                ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Throwable $e) {
            Log::error('Company profile update failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Unable to update company profile. Please try again.',
                ]);
        }
    }

    /**
     * AJAX upload for logo, cover image and business license.
     */
    public function uploadImage(Request $request)
    {
        try {
            $type = $request->input('type');

            $rules = match ($type) {
                'logo' => ['logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'],
                'cover' => ['logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'],
                'license' => ['logo' => 'required|file|mimes:pdf,jpeg,png,jpg,webp|max:5120'],
                default => ['type' => 'in:logo,cover,license'],
            };

            $request->validate($rules);

            $company = auth()->user()->company;

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found.',
                ], 404);
            }

            $directories = [
                'logo' => 'companies/logos',
                'cover' => 'companies/covers',
                'license' => 'companies/licenses',
            ];

            $columns = [
                'logo' => 'logo',
                'cover' => 'cover_image',
                'license' => 'business_license',
            ];

            $path = $request->file('logo')->store(
                $directories[$type],
                'public'
            );

            $company->{$columns[$type]} = $path;
            $company->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst($type) . ' updated successfully!',
                'path' => $path,
                'url' => asset('storage/' . ltrim($path, '/')),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Company media upload failed', [
                'user_id' => auth()->id(),
                'type' => $request->input('type'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed. Please try again.',
            ], 500);
        }
    }

    /**
     * AJAX remove for logo, cover image and business license.
     */
    public function removeImage(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:logo,cover,license',
            ]);

            $company = auth()->user()->company;

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found.',
                ], 404);
            }

            $field = match ($request->type) {
                'logo' => 'logo',
                'cover' => 'cover_image',
                'license' => 'business_license',
            };

            if ($company->{$field}) {
                Storage::disk('public')->delete($company->{$field});
            }

            $company->update([$field => null]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' removed successfully!',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Company media removal failed', [
                'user_id' => auth()->id(),
                'type' => $request->input('type'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to remove file. Please try again.',
            ], 500);
        }
    }

    public function verify()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {

            return response()->json([
                'success' => false,
                'message' => 'Company not found.',
            ], 404);
        }

        if (!$company->is_complete) {

            return response()->json([
                'success' => false,
                'message' => 'Please complete your company profile first.',
            ], 422);
        }

        if ($company->is_suspended) {

            return response()->json([
                'success' => false,
                'message' => 'This company account is currently suspended.',
            ], 422);
        }

        if ($company->is_fraud) {

            return response()->json([
                'success' => false,
                'message' => 'This company has been flagged for fraud.',
            ], 422);
        }

        if ($company->verification_status === 'pending') {

            return response()->json([
                'success' => false,
                'message' => 'Verification request is already pending.',
            ], 422);
        }

        if ($company->verification_status === 'verified') {

            return response()->json([
                'success' => false,
                'message' => 'Company is already verified.',
            ], 422);
        }

        $company->update([
            'verification_status' => 'pending',
            'verification_requested_at' => now(),
            'verification_rejection_reason' => null,
        ]);

        /*
         * Notify all admins.
         */
        $this->notificationService->sendToAdmins(
            'verification',
            'New Company Verification Request',
            $company->name . ' has requested company verification.',
            route('admin.companies.show', $company->id),
            'shield-alt'
        );

        return response()->json([
            'success' => true,
            'message' => 'Verification request submitted successfully. Our admin team will review your company.',
        ]);
    }

    public function checkProfileComplete()
    {
        $company = auth()->user()->company;

        return response()->json([
            'success' => true,
            'complete' => (bool) optional($company)->is_complete,
            'percentage' => (int) ($company->completion_percentage ?? 0),
        ]);
    }

    private function storeUploadedFile(Request $request, Company $company, array &$validated, string $field, string $directory): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        if ($company->{$field}) {
            Storage::disk('public')->delete($company->{$field});
        }

        $validated[$field] = $request->file($field)->store($directory, 'public');
    }

    private function getIndustries(): array
    {
        return [
            'Information Technology' => 'Information Technology',
            'Finance & Banking' => 'Finance & Banking',
            'Healthcare' => 'Healthcare',
            'Education' => 'Education',
            'Manufacturing' => 'Manufacturing',
            'Retail' => 'Retail',
            'Construction' => 'Construction',
            'Hospitality' => 'Hospitality',
            'Media & Communications' => 'Media & Communications',
            'Government' => 'Government',
            'NGO' => 'NGO',
            'Real Estate' => 'Real Estate',
            'Transportation' => 'Transportation',
            'Energy & Utilities' => 'Energy & Utilities',
            'Agriculture' => 'Agriculture',
            'Legal' => 'Legal',
            'Consulting' => 'Consulting',
            'Other' => 'Other',
        ];
    }

    private function getCompanySizes(): array
    {
        return [
            '1-10' => '1-10 employees',
            '11-50' => '11-50 employees',
            '51-200' => '51-200 employees',
            '201-500' => '201-500 employees',
            '5000+' => '5000+ employees',
        ];
    }
}
