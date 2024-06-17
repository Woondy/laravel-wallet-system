<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\WithdrawRequest;
use App\Http\Requests\BalanceRequest;
use App\Http\Resources\ApiResource;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use App\Services\WalletService;
use App\DTO\BalanceRequestDTO;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function deposit(DepositRequest $request, $id)
    {
        $wallet = Wallet::findOrFail($id);
        $this->walletService->deposit($wallet, $request->amount);
        return ApiResource::success('Deposit successful');
    }

    public function withdraw(WithdrawRequest $request, $id)
    {
        $wallet = Wallet::findOrFail($id);
        $this->walletService->withdraw($wallet, $request->amount);
        return ApiResource::success('Withdrawal successful');
    }

    public function balance(BalanceRequest $request, $id)
    {
        $dto = BalanceRequestDTO::fromRequest($id, $request->validated());

        // Follow User Timezone
        $userTimezone = 'Asia/Singapore';

        $wallet = Wallet::findOrFail($dto->id);
        $transactions = $wallet->transactions()
            ->when($dto->fromDate && $dto->toDate, function ($query) use ($dto, $userTimezone) {
                $query->whereDate('created_at', '>=', Carbon::parse($dto->fromDate, $userTimezone)->startOfDay()->utc())
                    ->whereDate('created_at', '<=', Carbon::parse($dto->toDate, $userTimezone)->endOfDay()->utc());
            })
            ->when($dto->type !== null, function ($query) use ($dto) {
                $query->where('type', $dto->type);
            })
            ->orderByDesc('created_at')
            ->paginate($dto->perPage, ['*'], 'page', $dto->page);

        $wallet->setRelation('transactions', $transactions);

        return ApiResource::success('Balance and transactions retrieved', [
            'wallet' => new WalletResource($wallet),
        ]);
    }
}
