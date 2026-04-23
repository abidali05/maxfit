<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    public function __invoke()
    {
        $plans = Plan::where('status', 'active')->get();

        foreach ($plans as $plan) {
            $plan->features = json_decode($plan->features);
        }

        return $this->success($plans, 'Plans fetched successfully', 200);
    }
}
