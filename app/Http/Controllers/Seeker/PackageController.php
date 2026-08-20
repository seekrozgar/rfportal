<?php
// app/Http/Controllers/Seeker/PackageController.php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::seeker()->active()->orderBy('display_order')->get();
        $activeSubscription = Subscription::where('user_id', Auth::id())
            ->where('type', 'seeker')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();

        return view('seeker.packages.index', compact('packages', 'activeSubscription'));
    }

    public function buy($packageId)
    {
        $package = Package::findOrFail($packageId);

        $existing = Subscription::where('user_id', Auth::id())
            ->where('type', 'seeker')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();

        if ($existing) {
            return redirect()->route('seeker.packages')
                ->with('error', 'You already have an active subscription.');
        }

        return view('seeker.packages.buy', compact('package'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())
            ->where('type', 'seeker')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seeker.packages.subscriptions', compact('subscriptions'));
    }

    public function activeSubscription()
    {
        $subscription = Subscription::where('user_id', Auth::id())
            ->where('type', 'seeker')
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('package')
            ->first();

        return view('seeker.packages.active', compact('subscription'));
    }
}
