<?php

namespace Bga\Games\AgileAndCo;

interface IGlobalVariable
{
    public function read() : mixed ;

    public function write(mixed $value) : void;

    public function delete() : void;
}