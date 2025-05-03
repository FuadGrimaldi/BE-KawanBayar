<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PinUpdateRequest;
use Illuminate\Http\Request;
use App\Helpers\ResponseCostum;
use App\Http\Resources\WalletResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class WalletController extends Controller
{

    public function updatePin(PinUpdateRequest $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return ResponseCostum::error(null, 'User not found', 404);
            }
            $data = $request->validated();
            $user->wallet->update([
                'pin' => ($data['new_pin'])
            ]);

            return ResponseCostum::success(null,'Pin wallet updated successfully', 200);
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error in update: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return ResponseCostum::error(null, 'An error occurred: ' . $e->getMessage(), 500);
        }
    }
}
