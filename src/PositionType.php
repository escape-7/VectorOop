<?php

namespace VectorOop;

class PositionType
{
    public readonly int $id;
    public readonly string $title;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

}