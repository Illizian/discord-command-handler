<?php

namespace App\DiscordCommands;

use App\Contracts\DiscordCommand;
use App\DTO\DiscordInteraction;
use Illuminate\Http\JsonResponse;

final class AnsweredMe implements DiscordCommand
{
    public static function handle(DiscordInteraction $interaction): JsonResponse
    {
        return response()->error('`/answered me` :: We\'ve not implemented this feature yet...');
    }
}
