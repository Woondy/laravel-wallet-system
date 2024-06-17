<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\WalletController;

Route::prefix('wallets')->group(function () {
    Route::post('{id}/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('{id}/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::get('{id}/balance', [WalletController::class, 'balance'])->name('wallet.balance');
});
