<?php

namespace Bga\Games\AgileAndCo;

readonly class Card
{
    public function __construct(int $id, string $type, string $name, int $playerId, string $location, int $index)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->fullName = $type . '_' . $name;
        $this->playerId = $playerId;
        $this->location = $location;
        $this->index = $index;
    }

    public int $id;
    public string $type;
    public string $name;
    public string $fullName;
    public int $playerId;
    public string $location;
    public int $index;

    public function __toString(): string
    {
        return json_encode(get_object_vars($this));
    }
}