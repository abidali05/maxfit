<?php

namespace App\Http\Controllers;

use App\Models\DailyAssessment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        $assessments = DailyAssessment::with(['user', 'set', 'exercise'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        return view('user_assessments.index', compact('assessments', 'date'));
    }
}
