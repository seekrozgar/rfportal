<?php
// app/Http/Controllers/Employer/PersonalProfileController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PersonalProfileController extends Controller
{
    /**
     * ✅ Show personal profile edit form
     */
    public function edit()
    {
        $user = auth()->user();
        return view('employer.profile.edit', compact('user'));
    }

    /**
     * ✅ Update personal information only (Excluding Email)
     */
    public function updateInfo(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'designation' => 'nullable|string|max:255',
            ]);

            // ✅ Update only allowed fields
            $updateData = [
                'name' => $validated['name'],
            ];

            // ✅ Only add phone if present
            if (isset($validated['phone'])) {
                $updateData['phone'] = $validated['phone'];
            }

            // ✅ Only add designation if present
            if (isset($validated['designation'])) {
                $updateData['designation'] = $validated['designation'];
            }

            $user->update($updateData);

            Log::info('✅ Personal info updated', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return redirect()->route('employer.profile.edit')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Personal information updated successfully!'
                ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator, 'info')
                ->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Info update failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * ✅ Update password only
     */
    public function updatePassword(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'current_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            // ✅ Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['current_password' => 'Current password is incorrect.'], 'password')
                    ->with('toast', [
                        'type' => 'error',
                        'message' => 'Current password is incorrect.'
                    ]);
            }

            // ✅ Update password
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

            Log::info('✅ Password updated', ['user_id' => $user->id]);

            return redirect()->route('employer.profile.edit')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Password changed successfully!'
                ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator, 'password')
                ->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Password update failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    /**
     * ✅ Upload avatar only - Fixed: Properly stores avatar path
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $user = auth()->user();

            // ✅ Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // ✅ Upload new avatar with unique name
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');

            // ✅ Update user with avatar path
            $user->update(['avatar' => $path]);

            Log::info('✅ Avatar uploaded', [
                'user_id' => $user->id,
                'path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'avatar' => asset('storage/' . $path),
                'avatar_path' => $path
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Avatar upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * ✅ Remove avatar
     */
    public function removeAvatar(Request $request)
    {
        try {
            $user = auth()->user();

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                $user->update(['avatar' => null]);
            }

            Log::info('✅ Avatar removed', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Avatar removal failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }
}
