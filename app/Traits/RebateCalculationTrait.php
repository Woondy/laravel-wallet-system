<?php

namespace App\Traits;

trait RebateCalculationTrait
{
    public function calculateRebate(float $amount): float
    {
        // 1% from deposit amount
        $rebateAmount = $amount * 0.01;
        return number_format($rebateAmount, 2, '.', '');
    }
}
