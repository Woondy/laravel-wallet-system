<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

use App\Enums\TransactionType;
use App\Models\Wallet;
use App\Models\Transaction;

class CalculateRebate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $wallet;
    protected $rebateAmount;

    /**
     * Create a new job instance.
     */
    public function __construct(Wallet $wallet, $rebateAmount)
    {
        $this->wallet = $wallet;
        $this->rebateAmount = $rebateAmount;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $this->wallet->lockForUpdate();

            $this->wallet->increment('balance', $this->rebateAmount);
            $this->wallet->transactions()->create([
                'type' => TransactionType::REBATE,
                'amount' => $this->rebateAmount,
            ]);
        });
    }
}
