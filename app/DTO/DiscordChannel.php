<?php

namespace App\DTO;

final class DiscordChannel
{
    public function __construct(
        public string $id,
        public string $name,
        public int $type,
        public ?string $owner_id,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            type: $data['type'],
            owner_id: $data['owner_id'] ??= null,
        );
    }
}
