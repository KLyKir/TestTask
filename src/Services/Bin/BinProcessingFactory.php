<?php

namespace App\Services\Bin;

use App\Services\Bin\Enum\BinSourceEnum;
use App\Services\Bin\Interfaces\BinInterface;
use App\Services\Bin\Processor\ApiNinjasProcessor;

readonly class BinProcessingFactory
{
    public function __construct(public ApiNinjasProcessor $binlist)
    {
    }

    public function getProcessor(BinSourceEnum $binSource): BinInterface
    {
        return match ($binSource) {
            BinSourceEnum::BIN_LIST => $this->binlist,
        };
    }
}