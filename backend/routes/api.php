<?php

use App\Http\Controllers\Api\SuccessfulEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('emails', SuccessfulEmailController::class);
});
