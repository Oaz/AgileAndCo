<?php

namespace Bga\Games\AgileAndCo\Tests;
use Bga\Games\AgileAndCo\IGlobalVariable;
class FakeGlobalVariable implements IGlobalVariable
{
    private mixed $value = 0;
    public function read(): mixed
    {
        return $this->value;
    }

    public function write(mixed $value): void
    {
        $this->value = $value;
    }

    public function delete(): void
    {
        // TODO: Implement delete() method.
    }
}