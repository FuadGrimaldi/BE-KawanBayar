<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InternetHistoryResource extends JsonResource
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
                'username' => $this->user->username,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone_number' => $this->user->phone_number,
            ] : null,
            'data_plan' => $this->dataPlan ? [
                'name' => $this->dataPlan->name,
                'price' => $this->dataPlan->price,
                'provider' => $this->dataPlan->operatorCard ? [
                    'name' => $this->dataPlan->operatorCard->name,
                ] : null,
            ] : null,
            'transaction_code' => $this->transaction_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
