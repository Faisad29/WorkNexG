<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth-rbac')->group(function (): void {
    Route::get('/ping', fn () => response()->json(['auth' => 'ok']));
});
