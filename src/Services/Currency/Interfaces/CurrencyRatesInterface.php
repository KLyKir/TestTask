<?php

namespace App\Services\Currency\Interfaces;

interface CurrencyRatesInterface
{
    public function getCurrencyRate(string $currency): float;
}