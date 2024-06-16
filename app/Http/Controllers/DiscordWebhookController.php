<?php

namespace App\Http\Controllers;

use App\DiscordCommands\Handler;
use App\DTO\DiscordInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscordWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Handler $handler): JsonResponse
    {
        $interaction = DiscordInteraction::make($request->json()->all());

        // @TODO: Need to refactor, so that the interaction can create a route independantly
        //        At the moment, we rely on the CommandInteraction to generate this but
        //        maybe we don't need DiscordInteraction at all, or rather the seperation?
        if ($interaction->type === 1) {
            return $handler->handle('/1', $interaction);
        }

        $command = $interaction->toCommandInteraction();
        $route = "/{$interaction->type}{$command->route}";

        return $handler->handle($route, $interaction);
    }
}
