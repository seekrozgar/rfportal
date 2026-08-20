<?php
// app/Http/Controllers/Admin/Location/CityController.php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $stateId = $request->state_id;
        $state = $stateId ? State::with('country')->find($stateId) : null;

        $cities = City::with('state.country')
            ->when($stateId, function ($query) use ($stateId) {
                return $query->where('state_id', $stateId);
            })
            ->orderBy('name')
            ->paginate(25);

        $states = State::active()->with('country')->orderBy('name')->get();
        $countries = Country::active()->orderBy('name')->get();

        return view('admin.location.cities.index', compact('cities', 'states', 'countries', 'state'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'state_id' => 'required|exists:states,id',
                'name' => 'required|string|max:255|unique:cities,name,NULL,id,state_id,' . $request->state_id,
            ]);

            City::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'City added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, City $city)
    {
        try {
            $request->validate([
                'state_id' => 'required|exists:states,id',
                'name' => 'required|string|max:255|unique:cities,name,' . $city->id . ',id,state_id,' . $request->state_id,
            ]);

            $city->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'City updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(City $city)
    {
        try {
            $stateId = $city->state_id;
            $city->delete();

            return response()->json([
                'success' => true,
                'message' => 'City deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(City $city)
    {
        try {
            $city->update(['is_active' => !$city->is_active]);

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

    // ✅ Get states by country (for dropdown)
    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($states);
    }

    // ✅ Get state info with country_id (for edit)
    public function getStateInfo($stateId)
    {
        $state = State::findOrFail($stateId);
        return response()->json([
            'id' => $state->id,
            'name' => $state->name,
            'country_id' => $state->country_id
        ]);
    }

    public function getByState($stateId)
    {
        $cities = City::where('state_id', $stateId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($cities);
    }
}
