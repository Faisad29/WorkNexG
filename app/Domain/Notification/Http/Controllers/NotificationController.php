<?php

namespace App\Domain\Notification\Http\Controllers;

use App\Domain\Notification\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController
{
    public function __construct(private readonly NotificationService $service)
    {
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'channel' => ['required', 'in:whatsapp,sms,email'],
            'recipient' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        $this->service->send($validated['channel'], $validated['recipient'], $validated['message']);

        return response()->json(['message' => 'queued']);
    }
}
