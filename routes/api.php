<?php

use App\DTO\DiscordInteraction;
use App\Http\Middleware\DiscordValidateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(DiscordValidateRequest::class)->post('/webhook', function (Request $request) {
    $interaction = DiscordInteraction::make($request->json()->all());

    if ($interaction->type === 1) {
        return response()->json(['type' => 1]);
    }

    if ($interaction->type === 2) {
        $command = $interaction->toCommandInteraction();

        return response()->json([
            'type' => 4,
            'data' => [
                'content' => "Thanks, <@{$interaction->member->discord_id}>, you're User#{$interaction->member->id}",
            ],
        ]);
    }
});
