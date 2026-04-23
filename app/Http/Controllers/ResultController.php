<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompetitionResult;
use App\Repositories\Contracts\ResultRepositoryInterface;

class ResultController extends Controller
{
    protected $resultOption;

    public function __construct(ResultRepositoryInterface $resultOption)
    {
        $this->resultOption = $resultOption;
    }

    public function index()
    {
        $results = $this->resultOption->get_results();
        return view('results.index', compact('results'));
    }

    public function update(Request $request, $competitionId)
    {
        $request->validate([
            'results' => 'required|array',
            'results.*.competition_user_id' => 'required|exists:competition_users,id',
            'results.*.percentage' => 'required|string|max:255',
            'results.*.per_min' => 'required|string|max:255',
            'results.*.position' => 'required|string|max:255',
        ]);

        foreach ($request->results as $entry) {
            CompetitionResult::updateOrCreate(
                [
                    'competition_user_id' => $entry['competition_user_id']
                ],
                [
                    'percentage' => $entry['percentage'],
                    'per_min' => $entry['per_min'],
                    'position' => $entry['position']
                ]
            );
        }

        return redirect()->route('competitions.show', $competitionId)->with('success', 'Results saved successfully.');
    }
}
