<?php

namespace App\DiscordCommands;

use App\DTO\DiscordInteraction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Handler
{
    /**
     * @param <string,\App\Contracts\DiscordCommand>  $handlers
     */
    public function __construct(protected array $handlers = [])
    {
    }

    public function command(string $route, string $handler): void
    {
        $this->handlers[$route] = app()->make($handler);
    }

    public function handle(string $route, DiscordInteraction $interaction): JsonResponse
    {
        /* @var \App\Contracts\DiscordCommand|null */
        $handler = Arr::get($this->handlers, $route, null);
        if ($handler === null) {
            throw new RouteNotFoundException('DiscordCommand not found!');
        }

        return $handler->handle($interaction);
    }
}
