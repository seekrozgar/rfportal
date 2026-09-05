<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    /**
     * Display all packages (Pricing Plans)
     */
    public function index()
    {
        // ✅ Get all active packages
        $packages = Package::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        // ✅ Get featured package
        $featuredPackage = Package::where('is_active', true)
            ->where('is_featured', true)
            ->first();

        return view('frontend.packages.index', compact('packages', 'featuredPackage'));
    }

    /**
     * Show a single package details
     */
    public function show($id)
    {
        $package = Package::where('is_active', true)
            ->findOrFail($id);

        return view('frontend.packages.show', compact('package'));
    }

    /**
     * Buy/Subscribe to a package
     */
    public function buy($id)
    {
        $package = Package::where('is_active', true)
            ->findOrFail($id);

        // Redirect to checkout page
        return redirect()->route('checkout', ['package' => $package->id]);
    }
}
