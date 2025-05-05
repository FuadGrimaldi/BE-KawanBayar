<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Helpers\ResponseCostum;
use Illuminate\Support\Facades\Log;

class TransactionsController extends Controller
{
    public function showAllTransactionsByUser()
    {
        // Logic to retrieve all transactions
        // You can use a model to fetch data from the database
        // For example: $transactions = Transaction::all();
        try {
            $user = auth()->user(); // dari middleware jwt.verify
            if (!$user) {
                return ResponseCostum::error(null, 'User not found', 404);
            }
            $transactions = Transaction::where('user_id', $user->id)->get();
            if ($transactions->isEmpty()) {
                return ResponseCostum::error(null, 'No transactions found', 404);
            }
            // Return the transactions as a JSON response
            return ResponseCostum::success(TransactionResource::collection($transactions), 'All transactions retrieved successfully', 200);
        } catch (\Throwable $th) {
            Log::channel('daily')->error('Error in showAllTransactionsByUser: ' . $th->getMessage(), [
                'exception' => $th,
            ]);
            return ResponseCostum::error(null, 'An error occurred: ' . $th->getMessage(), 500);
        }
    }
    public function searchTransactionByCode(Request $request)
    {
        try {
            $user = auth()->user(); // Autentikasi via middleware

            if (!$user) {
                return ResponseCostum::error(null, 'User not found', 404);
            }

            // Validasi request
            $request->validate([
                'transaction_code' => 'required|string',
            ]);

            $transactionCode = $request->transaction_code;

            // Ambil transaksi berdasarkan kode dan user login
            $transaction = Transaction::where('user_id', $user->id)
                ->where('transaction_code', $transactionCode)
                ->first();

            if (!$transaction) {
                return ResponseCostum::error(null, 'Transaction not found', 404);
            }

            return ResponseCostum::success(new TransactionResource($transaction), 'Transaction retrieved successfully', 200);

        } catch (\Throwable $th) {
            Log::channel('daily')->error('Error in searchTransactionByCode: ' . $th->getMessage(), [
                'exception' => $th,
            ]);
            return ResponseCostum::error(null, 'An error occurred: ' . $th->getMessage(), 500);
        }
    }

}
