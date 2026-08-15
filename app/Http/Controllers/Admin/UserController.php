<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // ✅ TRADITIONAL: Index (Page Load)
    public function index()
    {
        $users = User::whereIn('role', ['superadmin', 'admin', 'author'])
            ->with('roles')
            ->latest()
            ->paginate(20);

        $roles = Role::whereIn('name', ['superadmin', 'admin', 'author'])->get();
        $menuItems = $this->menuItems;

        return view('admin.users.index', compact('users', 'roles', 'menuItems'));
    }

    // ✅ TRADITIONAL: Create (Page Load)
    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'author'])->get();
        $menuItems = $this->menuItems;

        return view('admin.users.create', compact('roles', 'menuItems'));
    }

    // ✅ TRADITIONAL: Store (Page Reload)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,author',
            'permissions' => 'nullable|array',
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'is_active' => true,
            'is_fraud' => false,
            'permissions' => $request->permissions ?? [],
            'email_verified_at' => null,
        ]);

        $user->assignRole($request->role);

        // Send welcome email
        $this->sendWelcomeEmail($user, $password);

        Log::info('✅ ADMIN/AUTHOR CREATED', ['user_id' => $user->id, 'role' => $request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully! A welcome email with password has been sent.');
    }

    // ✅ TRADITIONAL: Edit (Page Load)
    public function edit(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot edit Super Admin.');
        }

        $roles = Role::whereIn('name', ['admin', 'author'])->get();
        $menuItems = $this->menuItems;

        return view('admin.users.edit', compact('user', 'roles', 'menuItems'));
    }

    // ✅ TRADITIONAL: Update (Page Reload)
    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot edit Super Admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,author',
            'permissions' => 'nullable|array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'permissions' => $request->permissions ?? [],
        ]);

        $user->syncRoles([$request->role]);

        Log::info('✅ ADMIN/AUTHOR UPDATED', ['user_id' => $user->id, 'role' => $request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    // ✅ TRADITIONAL: Delete (Page Reload)
    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete Super Admin.');
        }

        $user->delete();

        Log::info('✅ ADMIN/AUTHOR DELETED', ['user_id' => $user->id]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    // ✅ HYBRID: AJAX - Toggle Status (No Page Reload)
    public function toggleStatusAjax(User $user)
    {
        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change status of Super Admin.'
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully!',
            'is_active' => $user->is_active,
            'badge' => $user->is_active ? 'Active' : 'Disabled',
            'badge_class' => $user->is_active ? 'badge-active' : 'badge-inactive',
            'icon' => $user->is_active ? 'ban' : 'check-circle',
            'button_title' => $user->is_active ? 'Disable' : 'Enable',
        ]);
    }

    // ✅ HYBRID: AJAX - Mark Fraud (No Page Reload)
    public function markFraudAjax(User $user)
    {
        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot mark Super Admin as fraud.'
            ], 403);
        }

        $user->update([
            'is_fraud' => !$user->is_fraud,
            'is_active' => $user->is_fraud ? false : $user->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->is_fraud ? 'User marked as fraud!' : 'User cleared from fraud!',
            'is_fraud' => $user->is_fraud,
            'is_active' => $user->is_active,
            'badge' => $user->is_fraud ? 'Fraud' : ($user->is_active ? 'Active' : 'Disabled'),
            'badge_class' => $user->is_fraud ? 'badge-fraud' : ($user->is_active ? 'badge-active' : 'badge-inactive'),
            'icon' => $user->is_fraud ? 'shield-alt' : 'exclamation-triangle',
            'button_title' => $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud',
            'color' => $user->is_fraud ? '#28a745' : '#e74c3c',
        ]);
    }

    // ✅ HYBRID: AJAX - Delete (No Page Reload)
    public function destroyAjax(User $user)
    {
        if ($user->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete Super Admin.'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }

    // ✅ HYBRID: AJAX - Resend Verification (No Page Reload)
    public function resendVerificationAjax(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already verified.'
            ]);
        }

        try {
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ TRADITIONAL: Profiles (Page Load)
    public function profiles()
    {
        $users = User::whereIn('role', ['employer', 'seeker'])->latest()->paginate(20);
        return view('admin.users.profiles', compact('users'));
    }

    // ✅ TRADITIONAL: Resend Verification (Page Reload) - Fallback
    public function resendVerification(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('admin.users.index')
                ->with('info', 'User is already verified.');
        }

        try {
            $user->sendEmailVerificationNotification();
            Log::info('📧 VERIFICATION EMAIL RESENT', ['user_id' => $user->id, 'email' => $user->email]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Verification email sent successfully to ' . $user->email);

        } catch (\Exception $e) {
            Log::error('❌ VERIFICATION EMAIL FAILED', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to send verification email. Error: ' . $e->getMessage());
        }
    }

    // ✅ Welcome Email
    private function sendWelcomeEmail($user, $password)
    {
        try {
            $verificationUrl = route('verification.verify', [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification())
            ]);

            Mail::send('emails.admin-welcome', [
                'user' => $user,
                'password' => $password,
                'verificationUrl' => $verificationUrl,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Welcome to Rozgar Finder - Your Account Details');
            });

            Log::info('✅ WELCOME EMAIL SENT', ['user_id' => $user->id, 'email' => $user->email]);

        } catch (\Exception $e) {
            Log::error('❌ WELCOME EMAIL FAILED', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    private $menuItems = [
        'dashboard' => 'Dashboard',
        'users' => 'Admin Users',
        'jobs' => 'General Jobs',
        'company-jobs' => 'Company Jobs',
        'scholarships' => 'Scholarships',
        'admissions' => 'Admissions',
        'results' => 'Results',
        'news' => 'News',
        'profiles' => 'User Profiles',
        'seo' => 'SEO',
        'faq' => 'FAQs',
        'languages' => 'Languages',
        'countries' => 'Countries',
        'states' => 'States',
        'cities' => 'Cities',
        'packages' => 'Packages',
        'payments-company' => 'Company Payments',
        'payments-seeker' => 'Seeker Payments',
        'attributes' => 'Job Attributes',
        'settings' => 'Site Settings',
    ];
}
