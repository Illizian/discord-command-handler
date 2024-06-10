<?php

namespace App\DTO;

use App\Models\User;
use Illuminate\Support\Arr;

final class CommandInteraction
{
    public function __construct(
        public string $name,
        public array $options,
    ) {
    }

    public static function make(array $data): self
    {
        $users = collect(Arr::get($data, 'resolved.users', []))
            ->mapWithKeys(fn ($user) => [
                $user['id'] => User::query()->firstOrCreate([

                    'discord_id' => $user['id'],
                ], [
                    'discord_id' => $user['id'],
                    'username' => $user['global_name'],
                ]),
            ]);

        $options = collect(Arr::get($data, 'options', []))->map(fn ($option) => [
            ...$option,
            // If option is a User type, resolve to the resolved.users
            'value' => $option['type'] === 6
                ? $users->get($option['value'])
                : $option['type'],
        ]);

        return new self($data['name'], $options->toArray());
    }
}
