<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TopUpController;
use App\Http\Controllers\Api\UserCustController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\WebHookController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\InternetController;

Route::post('register',[AuthController::class, 'register']);
Route::post('login',[AuthController::class, 'login']);
Route::post('webhooks',[WebHookController::class, 'update']);
Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);
Route::get('providers', [InternetController::class, 'showAllProviders']);
Route::get('internet/{provider}', [InternetController::class, 'showDataPlanByProvider']);


//harus menyertakan bearer token
Route::group(['middleware' => 'jwt.verify'], function($router) {
    Route::get('user/profile',[UserCustController::class, 'show']);
    Route::put('user/update-profile',[UserCustController::class, 'update']);
    Route::put('user/update-pin',[WalletController::class, 'updatePin']);
    Route::get('user/transaction-history',[TransactionsController::class, 'showAllTransactionsByUser']);
    Route::get('user/transaction-history/search',[TransactionsController::class, 'searchTransactionByCode']);
    Route::get('user/transfers-history', [TransferController::class, 'showAllTransferHistory']);
    Route::post('top_ups', [TopUpController::class, 'store']);
    Route::post('transfers', [TransferController::class, 'store']);
});


// Route::middleware('jwt.verify')->get('test', function (Request $request) {
//     return 'success';
// });
