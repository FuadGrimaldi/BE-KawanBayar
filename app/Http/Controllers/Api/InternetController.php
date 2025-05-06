<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPlan;
use App\Models\OperatorCard;
use App\Helpers\ResponseCostum;

class InternetController extends Controller
{
    public function showAllProviders() {
        try {
            $providers = OperatorCard::all();
            return ResponseCostum::success($providers, 'List of Providers', 200);
        } catch (\Throwable $th) {
            return ResponseCostum::error(null, 'Failed to retrieve providers', 500);
        }
    }
    public function showDataPlanByProvider($provider) {
        try {
            $dataPlans = DataPlan::where('operator_card_id', $provider)->get();
            if ($dataPlans->isEmpty()) {
                return ResponseCostum::error(null, 'No data plans found for this provider', 404);
            }
            return ResponseCostum::success($dataPlans, 'List of Data Plans', 200);        
        } catch (\Throwable $th) {
            return ResponseCostum::error(null, 'Failed to retrieve data plans', 500);
        }
    }
}
