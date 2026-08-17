<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCategory;
use App\Models\JobType;
use App\Models\JobShift;
use App\Models\ExperienceLevel;
use App\Models\Industry;
use App\Models\FunctionalArea;
use App\Models\Skill;
use App\Models\LanguageLevel;
use App\Models\CareerLevel;
use App\Models\DegreeLevel;
use App\Models\DegreeType;
use App\Models\MajorSubject;
use App\Models\ResultType;
use App\Models\MaritalStatus;
use App\Models\OwnershipType;
use App\Models\SalaryPeriod;
use App\Models\Gender;

class AttributeController extends Controller
{
    private $models = [
        'language-levels' => ['model' => LanguageLevel::class, 'view' => 'language-levels'],
        'career-levels' => ['model' => CareerLevel::class, 'view' => 'career-levels'],
        'functional-areas' => ['model' => FunctionalArea::class, 'view' => 'functional-areas'],
        'genders' => ['model' => Gender::class, 'view' => 'genders'],
        'industries' => ['model' => Industry::class, 'view' => 'industries'],
        'job-experience' => ['model' => ExperienceLevel::class, 'view' => 'job-experience'],
        'job-skills' => ['model' => Skill::class, 'view' => 'job-skills'],
        'job-types' => ['model' => JobType::class, 'view' => 'job-types'],
        'job-shifts' => ['model' => JobShift::class, 'view' => 'job-shifts'],
        'degree-levels' => ['model' => DegreeLevel::class, 'view' => 'degree-levels'],
        'degree-types' => ['model' => DegreeType::class, 'view' => 'degree-types'],
        'major-subjects' => ['model' => MajorSubject::class, 'view' => 'major-subjects'],
        'result-types' => ['model' => ResultType::class, 'view' => 'result-types'],
        'marital-status' => ['model' => MaritalStatus::class, 'view' => 'marital-status'],
        'ownership-types' => ['model' => OwnershipType::class, 'view' => 'ownership-types'],
        'salary-periods' => ['model' => SalaryPeriod::class, 'view' => 'salary-periods'],
    ];

    // ✅ Generic method to handle all attributes
    public function index($type)
    {
        if (!isset($this->models[$type])) {
            abort(404);
        }

        $model = $this->models[$type]['model'];
        $items = $model::orderBy('name')->paginate(20);
        $view = $this->models[$type]['view'];

        return view("admin.attributes.{$view}", compact('items', 'type'));
    }

    // ✅ Store method for all attributes
    public function store(Request $request, $type)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $model = $this->models[$type]['model'];
        $item = $model::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added successfully!',
            'item' => $item
        ]);
    }

    // ✅ Update method
    public function update(Request $request, $type, $id)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $model = $this->models[$type]['model'];
        $item = $model::findOrFail($id);
        $item->update(['name' => $request->name]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!'
        ]);
    }

    // ✅ Delete method
    public function destroy($type, $id)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
        }

        $model = $this->models[$type]['model'];
        $item = $model::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully!'
        ]);
    }

    // ✅ Toggle status
    public function toggleStatus($type, $id)
    {
        if (!isset($this->models[$type])) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 404);
        }

        $model = $this->models[$type]['model'];
        $item = $model::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated!',
            'is_active' => $item->is_active
        ]);
    }
}
