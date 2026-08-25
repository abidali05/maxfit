<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class PlansController extends Controller
{
    protected $planOption;

    public function __construct(PlanRepositoryInterface $planOption)
    {
        $this->planOption = $planOption;
    }

    public function index()
    {
        $plans = $this->planOption->get_plans();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        return view('plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'duration' => 'required|in:monthly,quarterly,yearly',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert features string to JSON array
        if (!empty($validated['features'])) {
            $featuresArray = array_map('trim', explode(',', $validated['features']));
            $validated['features'] = json_encode($featuresArray);
        }

        $this->planOption->store_plan($validated);
        Toastr::success('Plan created successfully', 'Success');
        return redirect()->route('plans.index');
    }

    public function edit(string $id)
    {
        $plan = $this->planOption->get_plan($id);
        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string', // comma-separated string input
            'duration' => 'required|in:monthly,quarterly,yearly',
            'status' => 'required|in:active,inactive',
        ]);

        // Convert comma-separated string to JSON array
        if (!empty($validated['features'])) {
            $featuresArray = array_map('trim', explode(',', $validated['features']));
            $validated['features'] = json_encode($featuresArray);
        } else {
            $validated['features'] = json_encode([]);
        }

        $this->planOption->update_plan($id, $validated);

        Toastr::success('Plan updated successfully', 'Success');
        return redirect()->route('plans.index');
    }

    public function destroy(string $id)
    {
        $this->planOption->delete_plan($id);
        Toastr::success('Plan deleted successfully', 'Success');
        return redirect()->route('plans.index');
    }
}
