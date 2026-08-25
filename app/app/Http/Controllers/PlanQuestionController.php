<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Contracts\PlanQuestionRepositoryInterface;

class PlanQuestionController extends Controller
{
    protected $planQues;

    public function __construct(PlanQuestionRepositoryInterface $planQues)
    {
        $this->planQues = $planQues;
    }

    public function index()
    {
        $plan_questions = $this->planQues->get_plan_questions();
        return view('plan_questions.index', compact('plan_questions'));
    }

    public function create()
    {
        return view('plan_questions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|in:input,textarea,selection',
            'answer_options' => 'nullable|string',
            'is_required' => 'nullable|boolean',
        ]);

        $this->planQues->store_plan_question($validated);
        Toastr::success('Plan Question created successfully', 'Success');
        return redirect()->route('plan-questions.index');
    }

    public function edit(string $id)
    {
        $plan_question = $this->planQues->get_plan_question($id);
        return view('plan_questions.edit', compact('plan_question'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|in:input,textarea,selection',
            'is_required' => 'nullable|boolean',
            'answer_options' => 'nullable|string',
        ]);

        if ($validated['type'] !== 'selection') {
            $validated['answer_options'] = null;
        }

        $this->planQues->update_plan_question($id, $validated);
        Toastr::success('Plan Question updated successfully', 'Success');
        return redirect()->route('plan-questions.index');
    }

    public function destroy(string $id)
    {
        $this->planQues->delete_plan_question($id);
        Toastr::success('Plan Question deleted successfully', 'Success');
        return redirect()->route('plan-questions.index');
    }
}
