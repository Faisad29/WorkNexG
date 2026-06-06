<?php

namespace App\Domain\Site\Http\Controllers;

use App\Domain\Site\Http\Requests\StoreSiteRequest;
use App\Domain\Site\Models\Site;
use App\Domain\Site\Services\SiteService;
use Illuminate\Http\JsonResponse;

class SiteController
{
    public function __construct(private readonly SiteService $service)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->service->list()]);
    }

    public function store(StoreSiteRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->service->create($request->validated())], 201);
    }
}
