<?php

namespace App\DiscordCommands;

use App\Contracts\DiscordCommand;
use App\DTO\DiscordInteraction;
use App\Http\Resources\UserDiscordResource;
use Illuminate\Http\JsonResponse;

final class AnsweredMe implements DiscordCommand
{
    public static function handle(DiscordInteraction $interaction): JsonResponse
    {
        return response()->json([
            'type' => 4,
            'data' => [
                'content' => "Hey {$interaction->member->username}, you can find it below:",
                'embeds' => [
                    new UserDiscordResource($interaction->member),
                ],
            ],
        ]);
    }
}
