<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class TopUpController extends Controller
{
    public function store(Request $request) {
        // $data = $request->all(); // to get data all
        $data = $request->only('amount','pin','payment_method_code'); // to get data by spesifik
        $validator = Validator::make($data,
        [
            'amount' => 'required|integer|min:10000',
            'pin' => 'required|digits:6',
            'payment_method_code' => 'required|in:bni_va,bca_va,bri_va,gopay',
        ]);
        if ($validator->fails()) {
            return response()->json(['erros' => $validator->messages()], 400);
        }
        $pinChecker = pinChecker($request->pin);
        
        if (!$pinChecker) {
            return response()->json(['message' => ' Your Pin is wrong']);
        }

        $transactionType = TransactionType::where('code','top_up')->first();
        $paymentMethod = PaymentMethod::where('code', $request->payment_method_code)->first();
        if (!$paymentMethod || !$transactionType) {
            return response()->json([
                'error' => true,
                'message' => 'Payment method or transaction type not found'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'payment_method_id' => $paymentMethod->id,
                'transaction_type_id' => $transactionType->id,
                'amount' => $request->amount,
                'transaction_code' => strtoupper(Str::random(10)),
                'description' => 'Top Up Via '.$paymentMethod->name,
                'status' => 'pending'
            ]);

            //call to midtrans
            $params = $this->buildMitransParameters([
                'transaction_code' => $transaction->transaction_code,
                'amount' => $transaction->amount,
                'payment_method' => $paymentMethod->code
            ]); 
            // $midtrans = $this->callMidtrans($params);   

            DB::commit();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi berhasil dibuat',
                'redirect_url' => $params['redirect_url'],
                'snap_token' => $params['token']
            ]);
        } catch (\Exception $e) {
            return response()->json ([
                'error' => true,
                'message' => 'Midtrans Error',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    // Use Snap API instead of Core API
    // https://api.sandbox.midtrans.com/v2/snap/v1/transactions
    // private function callMidtrans(array $params) {
    //     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //     \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
    //     \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
    //     \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_IS_3DS');
    //     \Midtrans\Config::$curlOptions = [CURLOPT_SSL_VERIFYPEER => false];
    
    //     Log::info('Midtrans Parameters:', $params);
    //     // dd($params);
    
    //     try {
    //         $createTransaction = \Midtrans\Snap::createTransaction($params);
    //         Log::info('Midtrans Response:', (array) $createTransaction);
    
    //         if (isset($createTransaction->token) && isset($createTransaction->redirect_url)) {
    //             return [
    //                 'error' => false,
    //                 'token' => $createTransaction->token,
    //                 'redirect_url' => $createTransaction->redirect_url
    //             ];
    //         } else {
    //             return [
    //                 'error' => true,
    //                 'message' => 'Midtrans response missing token or redirect_url',
    //                 'details' => (array) $createTransaction
    //             ];
    //         }
    //     } catch (\Exception $e) {
    //         return [
    //             'error' => true,
    //             'message' => 'Midtrans Error',
    //             'details' => $e->getMessage()
    //         ];
    //     }
    // }

    // Use Core API instead of Snap With endpoint
    // https://api.sandbox.midtrans.com/v2/charge
    // example payload
    // {
    //   "payment_type": "bank_transfer",
    //   "transaction_details": {
    //     "order_id": "TOPUP-123456",
    //     "gross_amount": 12000
    //   },
    //   "bank_transfer": {
    //     "bank": "bni"
    //   },
    //   "customer_details": {
    //     "first_name": "Fuad",
    //     "last_name": "Grimaldi",
    //     "email": "fuad@example.com"
    //   }
    // }
    // private function callMidtrans(array $params) {
    //     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //     \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
    //     \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
    //     \Midtrans\Config::$is3ds = (bool) env('MIDTRANS_IS_3DS');
    //     \Midtrans\Config::$curlOptions = [CURLOPT_SSL_VERIFYPEER => false];
    
    //     Log::info('Midtrans Parameters:', $params);
    
    //     try {
    //         $payload = [
    //             'payment_type' => 'bank_transfer',
    //             'transaction_details' => [
    //                 'order_id' => $params['transaction_code'],
    //                 'gross_amount' => (int) $params['amount'],
    //             ],
    //             'bank_transfer' => [
    //                 'bank' => str_replace('_va', '', $params['payment_method']) // e.g., 'bni'
    //             ],
    //             'customer_details' => [
    //                 'first_name' => auth()->user()->name,
    //                 'email' => auth()->user()->email
    //             ]
    //         ];
    
    //         $charge = \Midtrans\CoreApi::charge($payload);
    //         return [
    //             'error' => false,
    //             'data' => $charge
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             'error' => true,
    //             'message' => 'Midtrans Core API Error',
    //             'details' => $e->getMessage()
    //         ];
    //     }
    // }
    
    

    // private function buildMitransParameters(array $params) {
    //     $transactionDetails = [
    //         'order_id' => $params['transaction_code'],
    //         'gross_amount' => (int) $params['amount']
    //     ];

    //     $user = auth()->user();
    //     $splitName = $this->splitName($user->name);
    //     $customerDetails = [
    //         'first_name' => $splitName['first_name'],
    //         'last_name' => $splitName['last_name'],
    //         'email' => $user->email
    //     ];
    //     $enabledPayment = [
    //         $params['payment_method']
    //     ];

    //     return [
    //         'transaction_details' => $transactionDetails,
    //         'customer_details' => $customerDetails,
    //         'enabled_payments' => $enabledPayment
    //     ];

    // }
    private function buildMitransParameters(array $params) {
        $transactionDetails = [
            'order_id' => $params['transaction_code'],
            'gross_amount' => (int) $params['amount']
        ];

        $user = auth()->user();
        $splitName = $this->splitName($user->name);
        $customerDetails = [
            'first_name' => $splitName['first_name'],
            'last_name' => $splitName['last_name'],
            'email' => $user->email
        ];
        $enabledPayment = [
            $params['payment_method']
        ];
        $payload = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'enabled_payments' => $enabledPayment
        ];
    
        $serverKey = config('midtrans.server_key');
        $base64Auth = base64_encode($serverKey . ':');
    
        $response = Http::withOptions([
            'verify' => false
        ])->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64Auth
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $payload);
    
        if ($response->successful()) {
            return $response->json(); // berisi token & redirect_url
        }
    
        throw new \Exception('Failed to create Snap transaction: ' . $response->body());

    }

    private function splitName($fullName) {
        $name = explode(' ', $fullName);
        // 'simone philis ' => ['simone', 'philips]
        $lastName = count($name) > 1 ? array_pop($name) : $fullName;
        $firstName = implode(' ', $name);
        return [
            'first_name' => $firstName,
            'last_name' => $lastName
        ];
    }
}
