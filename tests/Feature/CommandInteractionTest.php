<?php

namespace Tests\Feature;

use App\DTO\CommandInteraction;
use App\Models\User;

test('CommandInteraction parses a command with User resolutions and resolves correctly', function () {
    $command = CommandInteraction::make([
        'type' => 1,
        'guild_id' => '111111111111111111',
        'id' => '2222222222222222222',
        'name' => 'command',
        'options' => [
            [
                'name' => 'subcommand',
                'type' => 1,
                'options' => [
                    [
                        'name' => 'user',
                        'type' => 6,
                        'value' => '3333333333333333333',
                    ],
                ],
            ],
        ],
        'resolved' => [
            'users' => [
                '3333333333333333333' => [
                    'avatar' => '47a73f94633a4dea4779d47c5d5af598',
                    'avatar_decoration_data' => null,
                    'clan' => null,
                    'discriminator' => '0',
                    'global_name' => 'Global name',
                    'id' => '3333333333333333333',
                    'public_flags' => 0,
                    'username' => 'globalname',
                ],
            ],
        ],
    ]);

    $resolutions = $command->resolve();

    expect($resolutions->has('3333333333333333333'))->toBeTrue();

    $user = User::query()->where('discord_id', '3333333333333333333')->first();
    expect($user)->not()->toBeNull();
    expect($user->username)->toBe('globalname');

});
