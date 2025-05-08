<?php

namespace App\Services\Currency;

use App\Services\Currency\Enum\CurrencySourceEnum;
use Exception;

readonly class CurrencyService
{
    /**
     * @throws Exception
     */
    public function getCurrencyRate(string $currency, CurrencyProcessingFactory $factory): float
    {
        foreach (CurrencySourceEnum::cases() as $source) {
            $processor = $factory->getProcessor($source);

            try {
                $rate = $processor->getCurrencyRate($currency);

                break;
            } catch (Exception) {
                continue;
            }
        }

        if (!isset($rate)) {
            throw new Exception('No available source for currency rate');
        }

        return $rate;
    }
}