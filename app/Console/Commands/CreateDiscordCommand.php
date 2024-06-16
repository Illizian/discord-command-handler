<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CreateDiscordCommand extends Command
{
    protected $signature = 'app:create-discord-command';

    protected $description = 'Pushes the command to Discord';

    const CONFIG = [
        'name' => 'answered',
        'description' => 'Basically stackoverflow...',
        'options' => [
            [
                'name' => 'mark',
                'description' => 'Mark this thread as answered.',
                'type' => 1,
                'options' => [
                    [
                        'name' => 'by',
                        'description' => 'Who helped?',
                        'type' => 6,
                        'required' => true,
                    ],
                ],
            ],
            [
                'name' => 'top',
                'description' => 'See Top 10 Answerers.',
                'type' => 1,
            ],
            [
                'name' => 'me',
                'description' => 'See your answers.',
                'type' => 1,
            ],
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $token = config('services.discord.api_token');
        $appId = config('services.discord.client_id');

        $response = Http::acceptJson()
            ->withHeaders([
                'Authorization' => "Bot $token",
            ])
            ->post(
                "https://discord.com/api/v10/applications/$appId/commands",
                self::CONFIG
            );

        dump($response->json());

        return 0;
    }
}
