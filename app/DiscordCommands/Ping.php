<?php

namespace App\DiscordCommands;

use App\Contracts\DiscordCommand;
use App\DTO\DiscordInteraction;
use Illuminate\Http\JsonResponse;

final class Ping implements DiscordCommand
{
    public static function handle(DiscordInteraction $interaction): JsonResponse
    {
        return response()->json(['type' => 1]);
    }
}
