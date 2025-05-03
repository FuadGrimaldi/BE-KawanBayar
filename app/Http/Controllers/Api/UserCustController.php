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
        try {
            $user = auth()->user(); // Ambil user dari token JWT

            if (!$user) {
                return ResponseCostum::error(null, 'User not found', 404);
            }

            $data = $request->validated();

            // Hash password sebelum disimpan
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return ResponseCostum::success(new UserResource($user), 'User updated successfully', 200);
        } catch (\Exception $e) {
            Log::channel('daily')->error('Error in update: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return ResponseCostum::error(null, 'An error occurred: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
