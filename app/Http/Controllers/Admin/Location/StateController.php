<?php
// app/Http/Controllers/Admin/Location/StateController.php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $countryId = $request->country_id;
        $country = $countryId ? Country::find($countryId) : null;

        $states = State::with('country')
            ->when($countryId, function ($query) use ($countryId) {
                return $query->where('country_id', $countryId);
            })
            ->orderBy('name')
            ->paginate(25);

        $countries = Country::active()->orderBy('name')->get();

        return view('admin.location.states.index', compact('states', 'countries', 'country'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => 'required|string|max:255|unique:states,name,NULL,id,country_id,' . $request->country_id,
                'code' => 'nullable|string|max:10',
            ]);

            State::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'State added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, State $state)
    {
        try {
            $request->validate([
                'country_id' => 'required|exists:countries,id',
                'name' => 'required|string|max:255|unique:states,name,' . $state->id . ',id,country_id,' . $request->country_id,
                'code' => 'nullable|string|max:10',
            ]);

            $state->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'State updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(State $state)
    {
        try {
            if ($state->cities()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete! This state has ' . $state->cities()->count() . ' city/cities.'
                ], 422);
            }

            $countryId = $state->country_id;
            $state->delete();

            return response()->json([
                'success' => true,
                'message' => 'State deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(State $state)
    {
        try {
            $state->update(['is_active' => !$state->is_active]);

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
