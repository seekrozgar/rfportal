<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LanguageLevel;
use App\Models\CareerLevel;
use App\Models\FunctionalArea;
use App\Models\Gender;
use App\Models\Industry;
use App\Models\ExperienceLevel;
use App\Models\Skill;
use App\Models\JobType;
use App\Models\JobShift;
use App\Models\DegreeLevel;
use App\Models\DegreeType;
use App\Models\MajorSubject;
use App\Models\ResultType;
use App\Models\MaritalStatus;
use App\Models\OwnershipType;
use App\Models\SalaryPeriod;
use Illuminate\Support\Str;


class AttributeController extends Controller
{
    // ============================================================
    // ✅ GET MODEL BY TYPE
    // ============================================================

    private function getModel($type)
    {
        $models = [
            'language-levels' => LanguageLevel::class,
            'career-levels' => CareerLevel::class,
            'functional-areas' => FunctionalArea::class,
            'genders' => Gender::class,
            'industries' => Industry::class,
            'job-experience' => ExperienceLevel::class,
            'job-skills' => Skill::class,
            'job-types' => JobType::class,
            'job-shifts' => JobShift::class,
            'degree-levels' => DegreeLevel::class,
            'degree-types' => DegreeType::class,
            'major-subjects' => MajorSubject::class,
            'result-types' => ResultType::class,
            'marital-status' => MaritalStatus::class,
            'ownership-types' => OwnershipType::class,
            'salary-periods' => SalaryPeriod::class,
        ];

        return $models[$type] ?? null;
    }

    private function getTitle($type)
    {
        $titles = [
            'language-levels' => 'Language Levels',
            'career-levels' => 'Career Levels',
            'functional-areas' => 'Functional Areas',
            'genders' => 'Genders',
            'industries' => 'Industries',
            'job-experience' => 'Job Experience',
            'job-skills' => 'Job Skills',
            'job-types' => 'Job Types',
            'job-shifts' => 'Job Shifts',
            'degree-levels' => 'Degree Levels',
            'degree-types' => 'Degree Types',
            'major-subjects' => 'Major Subjects',
            'result-types' => 'Result Types',
            'marital-status' => 'Marital Status',
            'ownership-types' => 'Ownership Types',
            'salary-periods' => 'Salary Periods',
        ];

        return $titles[$type] ?? ucfirst(str_replace('-', ' ', $type));
    }

    // ============================================================
    // ✅ INDEX METHODS FOR ALL ATTRIBUTES
    // ============================================================

    public function languageLevels()
    {
        $items = LanguageLevel::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'language-levels',
            'title' => 'Language Levels'
        ]);
    }

    public function careerLevels()
    {
        $items = CareerLevel::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'career-levels',
            'title' => 'Career Levels'
        ]);
    }

    public function functionalAreas()
    {
        $items = FunctionalArea::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'functional-areas',
            'title' => 'Functional Areas'
        ]);
    }

    public function genders()
    {
        $items = Gender::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'genders',
            'title' => 'Genders'
        ]);
    }

    public function industries()
    {
        $items = Industry::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'industries',
            'title' => 'Industries'
        ]);
    }

    public function jobExperience()
    {
        $items = ExperienceLevel::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'job-experience',
            'title' => 'Job Experience'
        ]);
    }

    public function jobSkills()
    {
        $items = Skill::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'job-skills',
            'title' => 'Job Skills'
        ]);
    }

    public function jobTypes()
    {
        $items = JobType::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'job-types',
            'title' => 'Job Types'
        ]);
    }

    public function jobShifts()
    {
        $items = JobShift::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'job-shifts',
            'title' => 'Job Shifts'
        ]);
    }

    public function degreeLevels()
    {
        $items = DegreeLevel::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'degree-levels',
            'title' => 'Degree Levels'
        ]);
    }

    public function degreeTypes()
    {
        $items = DegreeType::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'degree-types',
            'title' => 'Degree Types'
        ]);
    }

    public function majorSubjects()
    {
        $items = MajorSubject::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'major-subjects',
            'title' => 'Major Subjects'
        ]);
    }

    public function resultTypes()
    {
        $items = ResultType::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'result-types',
            'title' => 'Result Types'
        ]);
    }

    public function maritalStatus()
    {
        $items = MaritalStatus::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'marital-status',
            'title' => 'Marital Status'
        ]);
    }

    public function ownershipTypes()
    {
        $items = OwnershipType::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'ownership-types',
            'title' => 'Ownership Types'
        ]);
    }

    public function salaryPeriods()
    {
        $items = SalaryPeriod::orderBy('name')->paginate(20);
        return view('admin.attributes.index', [
            'items' => $items,
            'type' => 'salary-periods',
            'title' => 'Salary Periods'
        ]);
    }

    // ============================================================
    // ✅ AJAX CRUD OPERATIONS
    // ============================================================

    public function store(Request $request, $type)
    {
        try {

            $model = $this->getModel($type);
            if (!$model) {
                return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255|unique:' . (new $model)->getTable() . ',name'
            ]);

            $item = $model::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . uniqid(),
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Added successfully!',
                'item' => $item
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $type, $id)
    {
        try {


            $model = $this->getModel($type);
            if (!$model) {
                return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
            }

            $request->validate([
                'name' => 'required|string|max:255|unique:' . (new $model)->getTable() . ',name,' . $id
            ]);

            $item = $model::findOrFail($id);
            $item->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . uniqid()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($type, $id)
    {
        try {


            $model = $this->getModel($type);
            if (!$model) {
                return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
            }

            $item = $model::findOrFail($id);
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($type, $id)
    {
        try {

            $model = $this->getModel($type);
            if (!$model) {
                return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
            }

            $item = $model::findOrFail($id);
            $item->update(['is_active' => !$item->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated!',
                'is_active' => $item->is_active
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Bulk Import - FIXED
    public function import(Request $request, $type)
    {
        try {


            $model = $this->getModel($type);
            if (!$model) {
                return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
            }

            $request->validate([
                'names' => 'required|string'
            ]);

            $names = array_map('trim', explode("\n", $request->names));
            $count = 0;

            foreach ($names as $name) {
                if (!empty($name)) {
                    // ✅ Check if already exists
                    $exists = $model::where('name', $name)->first();
                    if (!$exists) {
                        $model::create([
                            'name' => $name,
                            'slug' => Str::slug($name) . '-' . uniqid(),
                            'is_active' => true
                        ]);
                        $count++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} items imported successfully!"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
