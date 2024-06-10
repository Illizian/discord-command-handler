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
        public ?DiscordChannel $channel,
        public ?User $member,
        public ?array $data,
    ) {
    }

    public static function make(array $json): self
    {
        $id = $json['id'] ??= null;
        $token = $json['token'] ??= null;
        $type = $json['type'] ??= null;
        $data = $json['data'] ??= null;
        $channel = $json['channel'] ??= null;

        $discordId = Arr::get($json, 'member.user.id', null);
        $discordUsername = Arr::get($json, 'member.user.global_name', null);
        // @NOTE: Not comfortable with DTOs creating Models but here we are
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
            channel: ($channel !== null)
                ? DiscordChannel::make($channel)
                : null,
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
