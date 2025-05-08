<?php

namespace App\Services\Currency\Processors;

use App\Services\Currency\Interfaces\CurrencyRatesInterface;
use Exception;

class FixerRateProcessor implements CurrencyRatesInterface
{
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @throws Exception
     */
    public function getCurrencyRate(string $currency): float
    {
        $currencyRate = $this->getHTTPData();

        if (!isset($currencyRate['rates'][$currency])) {
            throw new Exception('Currency rate not found');
        }

        return (float)$currencyRate['rates'][$currency];
    }

    /**
     * @throws Exception
     */
    public function getHTTPData(): array
    {
        $url = $this->config['url'] . '?access_key=' . $this->config['access_key'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);

        if ($output === false) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new Exception('Curl error: ' . $error);
        }

        curl_close($ch);

        return json_decode($output, true);
    }
}