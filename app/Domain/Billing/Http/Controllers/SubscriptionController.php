<?php

namespace App\Domain\Billing\Http\Controllers;

use App\Domain\Billing\Http\Requests\SubscribeRequest;
use App\Domain\Billing\Models\Subscription;
use App\Domain\Billing\Services\BillingService;
use Illuminate\Http\JsonResponse;

class SubscriptionController
{
    public function __construct(private readonly BillingService $service)
    {
    }

    public function index(): JsonResponse
    {
        $subscriptions = Subscription::query()->with('plan')->latest()->paginate(10);
        return response()->json(['data' => $subscriptions]);
    }

    public function store(SubscribeRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->service->subscribe($request->validated())], 201);
    }
}
