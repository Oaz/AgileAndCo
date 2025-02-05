<?php

namespace Bga\Games\AgileAndCo;

class GlobalVariable
{
    private mixed $game;
    private mixed $name;
    public function __construct($game, $name)
    {
        $this->game = $game;
        $this->name = $name;
    }

    public function read() : mixed {
        return $this->game->globals->get($this->name);
    }

    public function write(mixed $value) : void {
        $this->game->globals->set($this->name, $value);
    }

    public function delete() : void {
        $this->game->globals->delete($this->name);
    }
}