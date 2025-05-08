<?php

namespace App\DTO;

readonly class TransactionDTO
{
    public function __construct(
        public float $amount,
        public string $currency,
        public float $rate,
        public string $countryCode
    )
    {
    }
}