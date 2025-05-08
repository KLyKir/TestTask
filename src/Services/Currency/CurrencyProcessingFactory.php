<?php

namespace App\Services\Currency;

use App\Services\Currency\Enum\CurrencySourceEnum;
use App\Services\Currency\Interfaces\CurrencyRatesInterface;
use App\Services\Currency\Processors\FixerRateProcessor;

readonly class CurrencyProcessingFactory
{
    public function __construct(public FixerRateProcessor $fixerRate)
    {
    }

    public function getProcessor(CurrencySourceEnum $currencySource): CurrencyRatesInterface
    {
        return match ($currencySource) {
            CurrencySourceEnum::FIXER => $this->fixerRate,
        };
    }
}