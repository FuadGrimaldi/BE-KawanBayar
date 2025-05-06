<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseCostum;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCustController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user(); // dari middleware jwt.verify
            if (!$user) {
                return ResponseCostum::error(null, 'User not found', 404);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:15',
            ]);

            // Update user profile
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;

            $user->save();

            return ResponseCostum::success(new UserResource($user), 'Profile updated successfully', 200);
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error in updateProfile: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return ResponseCostum::error(null, 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $user = auth()->user(); // dari middleware jwt.verify
            if (!$user) {
                return ResponseCostum::error(null, 'User not found', 404);
            }
            return ResponseCostum::success(new UserResource($user), 'User retrieved successfully', 200);
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error in show: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return ResponseCostum::error(null, 'An error occurred: ' . $e->getMessage(), 500);
        }
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request)
    { 
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
