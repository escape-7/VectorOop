<?php

namespace VectorOop;

class Department
{
    private int $id;
    private string $text;

    /**
     * @var Position[]
     */
    private array $positions;

    /**
     * @param int $id
     * @param string $text
     * @param Position[] $positions
     */
    public function __construct(int $id, string $text, array $positions)
    {
        $this->id = $id;
        $this->text = $text;
        $this->positions = $positions;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }


}