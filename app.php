<?php

require_once __DIR__ . '/config.php';

require_once __DIR__ . '/src/Constants/EuCountries.php';
require_once __DIR__ . '/src/Services/Bin/Enum/BinSourceEnum.php';
require_once __DIR__ . '/src/Services/Currency/Enum/CurrencySourceEnum.php';

require_once __DIR__ . '/src/DTO/TransactionDTO.php';

require_once __DIR__ . '/src/Services/Bin/Interfaces/BinInterface.php';
require_once __DIR__ . '/src/Services/Currency/Interfaces/CurrencyRatesInterface.php';

require_once __DIR__ . '/src/Services/Bin/Processor/ApiNinjasProcessor.php';
require_once __DIR__ . '/src/Services/Bin/BinProcessingFactory.php';
require_once __DIR__ . '/src/Services/Bin/BinService.php';

require_once __DIR__ . '/src/Services/Currency/Processors/FixerRateProcessor.php';
require_once __DIR__ . '/src/Services/Currency/CurrencyProcessingFactory.php';
require_once __DIR__ . '/src/Services/Currency/CurrencyService.php';

require_once __DIR__ . '/src/Services/CalculationService.php';

use App\DTO\TransactionDTO;
use App\Services\Bin\BinProcessingFactory;
use App\Services\Bin\Processor\ApiNinjasProcessor;
use App\Services\Bin\BinService;
use App\Services\Currency\CurrencyProcessingFactory;
use App\Services\Currency\Processors\FixerRateProcessor;
use App\Services\Currency\CurrencyService;
use App\Services\CalculationService;

if ($argc < 2) {
    die("Please provide an input file\n");
}

$inputFile = $argv[1];

if (!file_exists($inputFile)) {
    die("Input file does not exist\n");
}

$config = require __DIR__ . '/config.php';

$binFactory = new BinProcessingFactory(new ApiNinjasProcessor($config['ninjas_api']));
$binService = new BinService();

$currencyFactory = new CurrencyProcessingFactory(new FixerRateProcessor($config['fixer_api']));
$currencyService = new CurrencyService();

foreach (file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $row) {
    $data = json_decode($row, true);

    if (!$data) {
        continue;
    }

    try {
        $bin = $data['bin'];
        $amount = (float)$data['amount'];
        $currency = $data['currency'];

        $countryCode = $binService->getCountryCode($bin, $binFactory);
        $rate = ($currency === 'EUR') ? 1.0 : $currencyService->getCurrencyRate($currency, $currencyFactory);

        $transaction = new TransactionDTO($amount, $currency, $rate, $countryCode);
        $calculationService = new CalculationService($transaction);

        echo number_format($calculationService->getCalculationCommission(), 2) . "\n";
    } catch (Exception $e) {
        echo "Error processing transaction: {$e->getMessage()}\n";
    }
}
