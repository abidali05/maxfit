<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\MedicalAssessmentQuestionRepositoryInterface;
use Brian2694\Toastr\Facades\Toastr;

class MedicalAssessmentQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $medical_asses;
    public function __construct(MedicalAssessmentQuestionRepositoryInterface $medical_asses)
    {
        $this->medical_asses = $medical_asses;
    }

    public function index()
    {
        $medical_assessment_questions = $this->medical_asses->get_medical_assessment_questions();
        return view('medical_assessment_questions.index', compact('medical_assessment_questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medical_assessment_questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assessment_type' => 'required|in:Assessment,Medical',
            'question' => 'required|string|max:255',
            'type' => 'required|in:input,textarea,selection',
            'answer_options' => 'nullable|string',
        ]);
        $validated['is_required'] = $request->input('assessment_type') === 'Medical' ? 0 : $request->has('is_required');

        $this->medical_asses->store_medical_assessment_question($validated);
        Toastr::success('Medical Assessment Question created successfully', 'Success');
        return redirect()->route('medical-assessment-questions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $medical_assessment_question = $this->medical_asses->get_medical_assessment_question($id);
        return view('medical_assessment_questions.edit', compact('medical_assessment_question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'assessment_type' => 'required|in:Assessment,Medical',
            'question' => 'required|string|max:255',
            'type' => 'required|in:input,textarea,selection',
            'is_required' => 'nullable|boolean',
            'answer_options' => 'nullable|string',
        ]);

        if ($validated['type'] !== 'selection') {
            $validated['answer_options'] = null;
        }

        $validated['is_required'] = $request->has('is_required') ? 1 : 0;

        $this->medical_asses->update_medical_assessment_question($id, $validated);
        Toastr::success('Medical Assessment Question updated successfully', 'Success');
        return redirect()->route('medical-assessment-questions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->medical_asses->delete_medical_assessment_question($id);
        Toastr::success('Medical Assessment Question deleted successfully', 'Success');
        return redirect()->route('medical-assessment-questions.index');
    }
}
