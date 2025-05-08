<?php

namespace App\Services\Bin\Processor;

use App\Services\Bin\Interfaces\BinInterface;
use Exception;

class ApiNinjasProcessor implements BinInterface
{
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @throws Exception
     */
    public function getCountryCode(string $bin): string
    {
        $binData = $this->getHttpData($bin);

        if (!isset($binData[0]['iso_code2'])) {
            throw new Exception('Bin not found!');
        }

        return $binData[0]['iso_code2'];
    }

    /**
     * @throws Exception
     */
    public function getHttpData(string $bin): array
    {
        $url = $this->config['url'] . '?bin=' . $bin;

        $apiKey = $this->config['access_key'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-Api-Key: $apiKey",
        ]);
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