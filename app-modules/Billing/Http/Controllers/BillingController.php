<?php

namespace AppModules\Billing\Http\Controllers;

use AppModules\Billing\Http\Requests\BillingChargeRequest;
use AppModules\Billing\Http\Requests\BillingDepositRequest;
use AppModules\Billing\Http\Requests\BillingProfitRequest;
use AppModules\Billing\Http\Requests\BillingReleaseRequest;
use AppModules\Billing\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BillingController
{
    public function __construct(protected BillingService $billingService)
    {
    }

    public function deposit(BillingDepositRequest $request): Response
    {
        $data = $request->validated();
        $amount = $data['amount'] ?? null;

        if (!$amount) {
            throw new UnprocessableEntityHttpException('Amount is required.');
        }

        $userId = Auth::id(); //todo get it through bridge from auth module
        $this->billingService->refill($userId, $amount);

        return response()->noContent();
    }

    public function balance(): JsonResponse
    {
        $userId = Auth::id();//todo get it through bridge from auth module
        $balance = $this->billingService->getBalance($userId);

        return response()->json(['balance' => $balance]); //todo please,make resource.
    }

    public function history(): JsonResponse
    {
        $userId = Auth::id();//todo get it through bridge from auth module

        $balance = $this->billingService->getBalance($userId);
        $transactions = $this->billingService->getTransactions($balance->id);

        return response()->json(['transactions' => $transactions]);//todo please,make resource.
    }

    /**
     * Списание средств при создании ордера.
     */
    public function charge(BillingChargeRequest $request): Response
    {
        $data = $request->validated();
        $userId = Auth::id();

        $success = $this->billingService->charge($userId, $data['order_id']);

        if (!$success) {
            throw new UnprocessableEntityHttpException('Insufficient funds.');
        }

        return response()->noContent();
    }

    /**
     * Возврат средств при отмене ордера.
     */
    public function release(BillingReleaseRequest $request): Response
    {
        $data = $request->validated();
        $userId = Auth::id();

        $this->billingService->release($userId, $data['order_id']);

        return response()->noContent();
    }

    /**
     * Начисление прибыли после исполнения трейда.
     */
    public function profit(BillingProfitRequest $request): Response
    {
        $data = $request->validated();
        $userId = Auth::id();

        $this->billingService->profit($userId, $data['trade_id']);

        return response()->noContent();
    }
}
