<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user ? [
                'email' => $this->user->email,
                'name' => $this->user->name,
                'username' => $this->user->username,
            ] : null,
            'transaction_type' => $this->transactionType ? [
                'name' => $this->transactionType->name,
                'code' => $this->transactionType->code,
            ] : null,
            'payment_method' => $this->paymentMethod ? [
                'name' => $this->paymentMethod->name,
                'code' => $this->paymentMethod->code,
            ] : null,
            'product' => $this->product ? [
                'name' => $this->product->name,
                'description' => $this->product->description,
                'price' => $this->product->price,
            ] : null,
            'amount' => $this->amount,
            'transaction_code' => $this->transaction_code,
            'description' => $this->description,
            'status' => $this->status,
            'transaction_date' => $this->created_at->toDateTimeString(),
        ];
    }
}
