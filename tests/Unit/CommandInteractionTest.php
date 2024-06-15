<?php

namespace Tests\Unit;

use App\DTO\CommandInteraction;

test('CommandInteraction parses a simple command', function () {
    $command = CommandInteraction::make([
        'type' => 1,
        'guild_id' => '111111111111111111',
        'id' => '2222222222222222222',
        'name' => 'command',
        'options' => [],
    ]);

    expect($command->route)->toBe('/command');
    expect($command->options->count())->toBe(0);
});

test('CommandInteraction parses a simple command with parameters', function () {
    $command = CommandInteraction::make([
        'type' => 1,
        'guild_id' => '111111111111111111',
        'id' => '2222222222222222222',
        'name' => 'command',
        'options' => [
            [
                'name' => 'parameter',
                'type' => 6,
                'value' => '333333333333333333',
            ],
        ],
    ]);

    expect($command->route)->toBe('/command');
    expect($command->options->count())->toBe(1);
    expect($command->options->has('parameter'))->toBeTrue();
    expect($command->options->get('parameter'))->toBe([
        'type' => 6,
        'value' => '333333333333333333',
    ]);
});

test('CommandInteraction parses a command with subcommand & parameters', function () {
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
                        'name' => 'parameter',
                        'type' => 6,
                        'value' => '333333333333333333',
                    ],
                ],
            ],
        ],
    ]);

    expect($command->route)->toBe('/command/subcommand');
    expect($command->options->count())->toBe(1);
    expect($command->options->has('parameter'))->toBeTrue();
    expect($command->options->get('parameter'))->toBe([
        'type' => 6,
        'value' => '333333333333333333',
    ]);
});
