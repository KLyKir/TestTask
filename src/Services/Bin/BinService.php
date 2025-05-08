<?php

namespace App\Services\Bin;

use App\Services\Bin\Enum\BinSourceEnum;
use Exception;

readonly class BinService
{
    /**
     * @throws Exception
     */
    public function getCountryCode(string $bin, BinProcessingFactory $factory): string
    {
        foreach (BinSourceEnum::cases() as $source) {
            $processor = $factory->getProcessor($source);

            try {
                $countryCode = $processor->getCountryCode($bin);

                break;
            } catch (Exception) {
                continue;
            }
        }

        if (!isset($countryCode)) {
            throw new Exception('No available source for get bin info');
        }

        return $countryCode;
    }
}