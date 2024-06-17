<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Enums\TransactionType;
use App\Exceptions\InsufficientFundsException;
use App\Jobs\CalculateRebate;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Traits\RebateCalculationTrait;

class WalletService
{
    use RebateCalculationTrait;

    public function deposit(Wallet $wallet, $amount)
    {
        DB::transaction(function () use ($wallet, $amount) {
            $wallet->lockForUpdate();

            $wallet->increment('balance', $amount);
            $wallet->transactions()->create([
                'type' => TransactionType::DEPOSIT,
                'amount' => $amount,
            ]);

            // Skip calculation if rebate amount is less than 0.01
            $rebateAmount = $this->calculateRebate($amount);
            if ($rebateAmount >= 0.01) {
                dispatch(new CalculateRebate($wallet, $rebateAmount));
            }
        });
    }

    public function withdraw(Wallet $wallet, $amount)
    {
        DB::transaction(function () use ($wallet, $amount) {
            $wallet->lockForUpdate();

            // Ensure that the wallet cannot be overdrawn
            if ($wallet->balance >= $amount) {
                $wallet->decrement('balance', $amount);
                $wallet->transactions()->create([
                    'type' => TransactionType::WITHDRAW,
                    'amount' => $amount,
                ]);
            } else {
                throw new InsufficientFundsException();
            }
        });
    }
}
