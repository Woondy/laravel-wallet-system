<?php

namespace App\Exceptions;

use Exception;

class InsufficientFundsException extends Exception
{
    public function __construct($message = "Your wallet balance is insufficient to cover this withdrawal.")
    {
        $this->message = $message;
    }
}
