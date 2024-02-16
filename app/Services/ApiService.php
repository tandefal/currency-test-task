<?php

namespace App\Services;

use App\Models\CurrencyRate;
use SimpleXMLElement;

class ApiService
{
    private $currencyRateModel;

    public function __construct(CurrencyRate $currencyRateModel)
    {
        $this->currencyRateModel = $currencyRateModel;
    }

    public function updateRatesFromApi()
    {
        $xmlData = file_get_contents('https://www.cbr.ru/scripts/XML_daily.asp');
        $rates = $this->parseXmlData($xmlData);
        $this->currencyRateModel->saveRatesToDatabase($rates);
    }

    public function getAllRates()
    {
        $cachedRates = $this->currencyRateModel->getAllRates();
        if (empty($cachedRates)) {
            $this->updateRatesFromApi();
            $cachedRates = $this->currencyRateModel->getAllRates();
        }
        return $cachedRates;
    }

    public function getRate($currencyCode)
    {
        $cachedRate = $this->currencyRateModel->getRate($currencyCode);
        if (empty($cachedRate)) {
            $this->updateRatesFromApi();
            $cachedRate = $this->currencyRateModel->getRate($currencyCode);
        }
        return $cachedRate;
    }

    private function parseXmlData($xmlData)
    {
        $rates = [];
        $xml = new SimpleXMLElement($xmlData);
        foreach ($xml->Valute as $valute) {
            $currencyCode = (string) $valute->CharCode;
            $rates[] = [
                'currency_code' => $currencyCode,
                'num_code' => (int) $valute->NumCode,
                'char_code' => (string) $valute->CharCode,
                'nominal' => (int) $valute->Nominal,
                'name' => (string) $valute->Name,
                'value' => str_replace(',', '.', (string) $valute->Value),
                'vunit_rate' => str_replace(',', '.', (string) $valute->VunitRate)
            ];
        }
        return $rates;
    }
}