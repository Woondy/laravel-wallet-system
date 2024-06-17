<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\TransactionResource;
use App\Http\Resources\PaginatedResource;

class WalletResource extends JsonResource
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
            // 'user_id' => $this->user_id,
            'balance' => $this->balance,
            'transactions' => new PaginatedResource($this->transactions, TransactionResource::class),
        ];
    }
}
