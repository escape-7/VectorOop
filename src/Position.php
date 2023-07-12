<?php

namespace VectorOop;

class Position
{
    protected int $id;
    protected PositionType $type;
    protected int $baseRate;
    protected int $coffeeCost;
    protected WorkResultType $resultType;
    protected int $resultCount;
    protected bool $isBoss;
    protected int $employeeCount;
    protected int $rank;

    public function __construct(
        int $id,
        PositionType $type,
        int $baseRate,
        int $coffeeCost,
        WorkResultType $resultType,
        int $resultCount,
        bool $isBoss,
        int $employeeCount,
        int $rank
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->baseRate = $baseRate;
        $this->coffeeCost = $coffeeCost;
        $this->resultType = $resultType;
        $this->resultCount = $resultCount;
        $this->isBoss = $isBoss;
        $this->employeeCount = $employeeCount;
        $this->rank = $rank;
    }

}