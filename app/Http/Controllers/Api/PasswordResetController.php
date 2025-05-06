<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use App\Helpers\ResponseCostum;
use App\Models\User;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return ResponseCostum::success(null, __($status), 200);
            } else {
                return ResponseCostum::error(null, __($status), 400);   
            }
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error in forgotPassword: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return ResponseCostum::error(null, 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password'       => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return ResponseCostum::success(null, __($status), 200);
            } else {
                return ResponseCostum::error(null, __($status), 400);
            }
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error in resetPassword: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return ResponseCostum::error(null, 'An error occurred: ' . $e->getMessage(), 500);
        }
    }
}
