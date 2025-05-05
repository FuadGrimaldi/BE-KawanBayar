<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferHistoryResource extends JsonResource
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
            'user_sender' => $this->sender ? [
                'email' => $this->sender->email,
                'username' => $this->sender->username,
            ] : null,
            'user_receiver' => $this->receiver ? [
                'email' => $this->receiver->email,
                'username' => $this->receiver->username,
            ] : null,
            'transaction_code' => $this->transaction_code,
            'transaction_date' => $this->created_at->toDateTimeString(),
        ];
    }
}
