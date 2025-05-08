<?php

namespace App\Services;

use App\Constants\EuCountries;
use App\DTO\TransactionDTO;

readonly class CalculationService
{
    public function __construct(public TransactionDTO $dto)
    {
    }

    public function getCalculationCommission(): float
    {
        $commission = $this->getCommission($this->dto->countryCode);
        $amountFixed = $this->getFixedAmount($this->dto->currency, $this->dto->rate, $this->dto->amount);

        return round($amountFixed * $commission, 2);
    }

    private function getCommission(string $countryCode): float
    {
        return in_array($countryCode, EuCountries::values()) ? 0.01 : 0.02;
    }

    private function getFixedAmount(string $currency, float $rate, float $amount): float
    {
        $fixedAmount = ($currency == 'EUR' || $rate == 0) ? $amount : $amount / $rate;

        return $fixedAmount;
    }
}