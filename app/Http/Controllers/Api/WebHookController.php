<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function update(Request $request)
{
    Log::info('Webhook Payload:', $request->all());

    $serverKey = env('MIDTRANS_SERVER_KEY');
    $signatureKey = $request->signature_key;

    $orderId = $request->order_id;
    $statusCode = $request->status_code;
    $grossAmount = $request->gross_amount;

    // Validasi Signature Key
    $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
    if ($hashed !== $signatureKey) {
        return response()->json(['message' => 'Invalid signature key'], 403);
    }

    $transactionStatus = $request->transaction_status;
    $type = $request->payment_type;
    $transactionCode = $orderId;
    $fraudStatus = $request->fraud_status;

    DB::beginTransaction();
    try {
        $status = null;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $status = 'success';
            }
        } else if ($transactionStatus == 'settlement') {
            $status = 'success';
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $status = 'pending';
        }

        $transaction = Transaction::where('transaction_code', $transactionCode)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->status != 'success') {
            $transactionMount = $transaction->amount;
            $userId = $transaction->user_id;

            $transaction->update(['status' => $status]);

            if ($status == 'success') {
                Wallet::where('user_id', $userId)->increment('balance', $transactionMount);
            }
        }

        DB::commit();
        return response()->json(['message' => 'Webhook processed']);
    } catch (\Throwable $th) {
        DB::rollBack();
        return response()->json(['message' => $th->getMessage()], 500);
    }
}


}
