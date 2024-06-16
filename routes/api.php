<?php

use App\Http\Controllers\DiscordWebhookController;
use App\Http\Middleware\DiscordValidateRequest;
use Illuminate\Support\Facades\Route;

Route::middleware(DiscordValidateRequest::class)->group(function () {
    Route::post('/webhook', DiscordWebhookController::class);
});
