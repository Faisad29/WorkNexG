<?php

use Illuminate\Support\Facades\Route;

Route::prefix('tenant')->group(function (): void {
    Route::get('/ping', fn () => response()->json(['tenant' => 'ok']));
});
