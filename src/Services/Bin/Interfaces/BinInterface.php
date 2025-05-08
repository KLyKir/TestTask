<?php

namespace App\Services\Bin\Interfaces;

interface BinInterface
{
    public function getCountryCode(string $bin): string;
}