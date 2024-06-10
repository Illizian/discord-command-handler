<?php

namespace App\DTO;

use App\Models\User;
use Illuminate\Support\Arr;

// @TODO: Refactor this to enable it to support multiple commands, for now it assumes the "answered" command
final class CommandInteraction
{
    public function __construct(
        public string $name,
        public User $by,
    ) {
    }

    public static function make(array $data): self
    {
        $byId = Arr::get($data, 'options.0.value', null);
        $byUsername = Arr::get($data, "resolved.users.$byId.global_name", null);
        // @NOTE: Not comfortable with DTOs creating Models but here we are
        $by = User::query()->firstOrCreate([
            'discord_id' => $byId,
        ], [
            'discord_id' => $byId,
            'username' => $byUsername,
        ]);

        return new self($data['name'], $by);
    }
}
