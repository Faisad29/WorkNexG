<?php

namespace App\Domain\Compliance\Http\Controllers;

use App\Domain\Compliance\Http\Requests\StoreDocumentRequest;
use App\Domain\Compliance\Models\Document;
use App\Domain\Compliance\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentController
{
    public function __construct(private readonly DocumentService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Document::query()->with('employee')->latest();
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->get('employee_id'));
        }
        return response()->json(['data' => $query->paginate(25)]);
    }

    public function store(StoreDocumentRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->service->create($request->validated())], 201);
    }
}
