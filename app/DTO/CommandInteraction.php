<?php

namespace App\DTO;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

// @TODO: Refactor this to enable it to support multiple commands, for now it assumes the "answered" command
final class CommandInteraction
{
    public function __construct(
        public string $route,
        public Collection $options,
        public Collection $resolved
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
            return new self(
                "$route/$name",
                collect($options)
                    ->mapWithKeys(
                        fn ($option) => [
                            $option['name'] => [
                                'type' => $option['type'],
                                'value' => $option['value'],
                            ],
                        ]
                    ),
                collect($resolutions)
            );
        }

        return self::make($data['options'][0], "$route/$name", $resolutions);
    }
}
