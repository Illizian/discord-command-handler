<?php

namespace App\Contracts;

use App\DTO\DiscordInteraction;
use Illuminate\Http\JsonResponse;

interface DiscordCommand
{
    public static function handle(DiscordInteraction $interaction): JsonResponse;
}
