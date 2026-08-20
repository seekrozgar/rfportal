<?php
// app/Http/Controllers/Admin/Location/CountryController.php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->paginate(25);
        return view('admin.location.countries.index', compact('countries'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:countries',
                'code' => 'required|string|max:3|unique:countries',
                'phone_code' => 'nullable|string|max:10',
            ]);

            Country::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Country added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, Country $country)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
                'code' => 'required|string|max:3|unique:countries,code,' . $country->id,
                'phone_code' => 'nullable|string|max:10',
            ]);

            $country->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Country updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Country $country)
    {
        try {
            if ($country->states()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete! This country has ' . $country->states()->count() . ' state(s).'
                ], 422);
            }

            $country->delete();

            return response()->json([
                'success' => true,
                'message' => 'Country deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(Country $country)
    {
        try {
            $country->update(['is_active' => !$country->is_active]);

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
