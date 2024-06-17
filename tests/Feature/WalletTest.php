<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

use App\Models\Wallet;
use App\Models\User;
use App\Traits\RebateCalculationTrait;

class WalletTest extends TestCase
{
    use RefreshDatabase, RebateCalculationTrait;

    private function createWallet($initialBalance = 0)
    {
        return Wallet::factory()->create(['balance' => $initialBalance]);
    }

    private function depositToWallet($wallet, $amount)
    {
        return $this->postJson(route('wallet.deposit', ['id' => $wallet->id]), ['amount' => $amount]);
    }

    private function withdrawFromWallet($wallet, $amount)
    {
        return $this->postJson(route('wallet.withdraw', ['id' => $wallet->id]), ['amount' => $amount]);
    }
    
    public function test_deposit_with_rebate($initialBalance = 0, $depositAmount = 100)
    {
        $wallet = $this->createWallet($initialBalance);
        $rebateAmount = $this->calculateRebate($depositAmount);

        $this->depositToWallet($wallet, $depositAmount)->assertStatus(200);

        $wallet->refresh();
        $expectedBalance = $initialBalance + $depositAmount + $rebateAmount;
        $this->assertEquals($expectedBalance, $wallet->balance);
    }

    public function test_deposit_without_rebate($initialBalance = 0, $depositAmount = 0.9)
    {
        $wallet = $this->createWallet($initialBalance);
        $rebateAmount = $this->calculateRebate($depositAmount);

        $this->depositToWallet($wallet, $depositAmount)->assertStatus(200);

        $wallet->refresh();
        $expectedBalance = $initialBalance + $depositAmount + $rebateAmount;
        $this->assertEquals($expectedBalance, $wallet->balance);
    }

    public function test_concurrent_deposits($initialBalance = 0, $depositAmount = 100, $concurrentRequests = 100)
    {
        $wallet = $this->createWallet($initialBalance);
        $rebateAmount = $this->calculateRebate($depositAmount);

        $responses = [];
        for ($i = 0; $i < $concurrentRequests; $i++) {
            $responses[] = $this->depositToWallet($wallet, $depositAmount);
        }

        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $wallet->refresh();
        $expectedBalance = $initialBalance + (($depositAmount + $rebateAmount) * $concurrentRequests);
        $this->assertEquals($expectedBalance, $wallet->balance);
    }

    public function test_withdraw($initialBalance = 100, $withdrawAmount = 50)
    {
        $wallet = $this->createWallet($initialBalance);

        $this->withdrawFromWallet($wallet, $withdrawAmount)->assertStatus(200);

        $wallet->refresh();
        $expectedBalance = $initialBalance - $withdrawAmount;
        $this->assertEquals($expectedBalance, $wallet->balance);
    }

    public function test_withdraw_with_insufficient_funds($initialBalance = 50, $withdrawAmount = 100)
    {
        $wallet = $this->createWallet($initialBalance);

        $this->withdrawFromWallet($wallet, $withdrawAmount)->assertStatus(400);

        $wallet->refresh();
        $expectedBalance = $initialBalance;
        $this->assertEquals($expectedBalance, $wallet->balance);
    }

    public function test_concurrent_withdraws($initialBalance = 10000, $withdrawAmount = 100, $concurrentRequests = 100)
    {
        $wallet = $this->createWallet($initialBalance);

        $responses = [];
        for ($i = 0; $i < $concurrentRequests; $i++) {
            $responses[] = $this->withdrawFromWallet($wallet, $withdrawAmount);
        }

        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $wallet->refresh();
        $expectedBalance = $initialBalance - ($withdrawAmount * $concurrentRequests);
        $this->assertEquals($expectedBalance, $wallet->balance);
    }
}
