<?php

namespace App\DTO;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class CommandInteraction
{
    public function __construct(
        public string $route,
        public Collection $options,
        protected array $resolved
    ) {
    }

    public static function make(array $data, string $route = '', array $resolved = []): self
    {
        $name = Arr::get($data, 'name', '');
        $options = Arr::get($data, 'options', []);
        $child = Arr::get($data, 'options.0.type', null);
        $resolutions = [
            ...$resolved,
            ...Arr::get($data, 'resolved', []),
        ];

        if (count($options) === 0 || $child !== 1) {
            return
                new self(
                    route: "$route/$name",
                    options: collect($options)
                        ->mapWithKeys(
                            fn ($option) => [
                                $option['name'] => [
                                    'type' => $option['type'],
                                    'value' => $option['value'],
                                ],
                            ]
                        ),
                    resolved: $resolutions
                );
        }

        return self::make($data['options'][0], "$route/$name", $resolutions);
    }

    /**
     * @return Collection<string, User>
     */
    public function resolve(): Collection
    {
        $users = Arr::get($this->resolved, 'users', []);

        return collect($users)
            ->mapWithKeys(fn ($user) => [
                $user['id'] => User::query()->firstOrCreate([
                    'discord_id' => $user['id'],
                ], [
                    'discord_id' => $user['id'],
                    'username' => $user['username'],
                ]),
            ]);
    }
}
