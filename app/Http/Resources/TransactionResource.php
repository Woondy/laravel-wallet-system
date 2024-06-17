<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\PaginatedResource;

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
            // 'id' => $this->id,
            // 'wallet_id' => $this->wallet_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
        ];
    }
}
