<?php

namespace App\DTO;

use App\Models\User;
use Exception;
use Illuminate\Support\Arr;

final class DiscordInteraction
{
    public function __construct(
        public string $id,
        public string $token,
        public int $type,
        public ?User $member,
        public ?array $data,
    ) {

    }

    public static function make(array $json): self
    {
        $id = Arr::get($json, 'id', null);
        $token = Arr::get($json, 'token', null);
        $type = Arr::get($json, 'type', null);
        $data = Arr::get($json, 'data', null);

        $discordId = Arr::get($json, 'member.user.id', null);
        $discordUsername = Arr::get($json, 'member.user.global_name', null);
        $member = $discordId && $discordUsername
            ? User::query()->firstOrCreate([
                'discord_id' => $discordId,
            ], [
                'discord_id' => $discordId,
                'username' => $discordUsername,
            ])
            : null;

        return new self(
            id: $id,
            token: $token,
            type: $type,
            member: $member,
            data: $data,
        );
    }

    public function toCommandInteraction(): ?CommandInteraction
    {
        throw_if($this->type !== 2, new Exception("DiscordInteraction must be of type 2, received {$this->type}."));
        throw_if($this->data === null, new Exception('DiscordInteraction must have a data payload!'));

        return CommandInteraction::make($this->data);
    }
}
