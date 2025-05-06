<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPlan;
use App\Models\OperatorCard;
use App\Helpers\ResponseCostum;
use App\Http\Resources\InternetHistoryResource;
use App\Http\Resources\DataPlanResource;
use App\Models\TransactionType;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\InternetHistory;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
            return ResponseCostum::success(DataPlanResource::collection($dataPlans), 'List of Data Plans', 200);
        } catch (\Throwable $th) {
            return ResponseCostum::error(null, 'Failed to retrieve data plans', 500);
        }
    }

    public function paymentInternet(Request $request)
    {
        try {
            $user = auth()->user();

            $data = $request->only('amount', 'pin', 'payment_method_code', 'data_plan_id');
            $validator = Validator::make($data, [
                'amount' => 'required|integer|min:10000',
                'pin' => 'required|digits:6',
                'payment_method_code' => 'required|in:bni_va,bca_va,bri_va,gopay,kawan_wallet',
                'data_plan_id' => 'required', // Pastikan data plan ada
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()], 400);
            }

            // Cek PIN
            if (!pinChecker($request->pin)) {
                return response()->json(['message' => 'Your PIN is wrong'], 403);
            }

            // Ambil data metode pembayaran & tipe transaksi
            $transactionType = TransactionType::where('code', 'internet')->first();
            $paymentMethod = PaymentMethod::where('code', $request->payment_method_code)->first();
            if (!$paymentMethod) {
                return ResponseCostum::error(null, 'Payment method not found', 404);
            }

            if (!$paymentMethod || !$transactionType) {
                return ResponseCostum::error(null, 'Payment method or transaction type not found', 404);
            }

            $transactionCode = strtoupper(Str::random(10));

            // Jika pembayaran menggunakan Kawan Wallet
            if ($paymentMethod->code === 'kawan_wallet') {
                return $this->handleWalletPayment($user, $request, $transactionType, $transactionCode);
            }

            // Jika pembayaran menggunakan metode lain (VA/Gopay via Midtrans)
            return $this->handleMidtransPayment($user, $request, $transactionType, $paymentMethod, $transactionCode);
            
        } catch (\Throwable $th) {
            return ResponseCostum::error(null, 'Payment failed: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Handle payment using Kawan Wallet
     */
    private function handleWalletPayment($user, Request $request, $transactionType, $transactionCode)
    {
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return ResponseCostum::error(null, 'Insufficient wallet balance', 400);
        }

        // Simpan riwayat internet
        InternetHistory::create([
            'user_id' => $user->id,
            'data_plan_id' => $request->data_plan_id,
            'transaction_code' => $transactionCode,
        ]);

        // Kurangi saldo wallet
        $wallet->balance -= $request->amount;
        $wallet->save();

        // Buat catatan transaksi
        Transaction::create([
            'user_id' => $user->id,
            'payment_method_id' => PaymentMethod::where('code', 'kawan_wallet')->first()->id,
            'transaction_type_id' => $transactionType->id,
            'amount' => $request->amount,
            'transaction_code' => $transactionCode,
            'description' => 'Internet Payment Via Kawan Wallet',
            'status' => 'success',
        ]);

        return ResponseCostum::success($wallet, 'Payment successful via wallet', 200);
    }

    /**
     * Handle payment using Midtrans
     */
    private function handleMidtransPayment($user, Request $request, $transactionType, $paymentMethod, $transactionCode)
    {
        // Simpan riwayat internet
        $dataPlan = DataPlan::find($request->data_plan_id);
        if (!$dataPlan) {
            return ResponseCostum::error(null, 'Data plan not found', 404);
        }

        // Simpan riwayat internet
    
        InternetHistory::create([
            'user_id' => $user->id,
            'data_plan_id' => $request->data_plan_id,
            'transaction_code' => $transactionCode,
        ]);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'transaction_type_id' => $transactionType->id,
            'amount' => $request->amount,
            'transaction_code' => $transactionCode,
            'description' => 'Internet Payment Via ' . $paymentMethod->name,
            'status' => 'pending',
        ]);

        $params = $this->buildMitransParameters([
            'transaction_code' => $transaction->transaction_code,
            'amount' => $transaction->amount,
            'payment_method' => $paymentMethod->code,
        ]);

        return ResponseCostum::success([
            'redirect_url' => $params['redirect_url'],
            'snap_token' => $params['token'],
        ], 'Payment initiated via Midtrans', 200);
    }


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
        $itemDetails = [
            [
                'id'       => 'internet_plan',
                'price'    => (int) $params['amount'],
                'quantity' => 1,
                'name'     => 'Pembayaran Internet via ' . strtoupper($params['payment_method']),
            ]
        ];
        $payload = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'enabled_payments' => $enabledPayment,
            'item_details' => $itemDetails,
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

    public function showAllInternetHistory() {
        try {
            $user = auth()->user();
            $internetHistories = InternetHistory::where('user_id', $user->id)->get();
            return ResponseCostum::success(InternetHistoryResource::collection($internetHistories), 'List of Internet History', 200);
        } catch (\Throwable $th) {
            return ResponseCostum::error(null, 'Failed to retrieve internet history', 500);
        }
    }
}
