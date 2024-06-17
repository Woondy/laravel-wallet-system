<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case REBATE = 'rebate';

    public static function getValues(): array
    {
        return [
            self::DEPOSIT->value,
            self::WITHDRAW->value,
            self::REBATE->value,
        ];
    }
}
