<?php

namespace App\Providers;

use App\DiscordCommands\AnsweredMark;
use App\DiscordCommands\AnsweredMe;
use App\DiscordCommands\AnsweredTop;
use App\DiscordCommands\Handler;
use App\DiscordCommands\Ping;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class DiscordCommandsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Response::macro('error', function (string $message) {
            return Response::json([
                'type' => 4,
                'data' => [
                    'flags' => 1 << 6,
                    'content' => $message,
                ],
            ]);
        });

        $this->app->singleton(Handler::class, function (Application $app) {
            return new Handler();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* @var Handler */
        $handler = $this->app->make(Handler::class);
        $handler->command('/1', Ping::class);
        $handler->command('/2/answered/mark', AnsweredMark::class);
        $handler->command('/2/answered/me', AnsweredMe::class);
        $handler->command('/2/answered/top', AnsweredTop::class);
    }
}
