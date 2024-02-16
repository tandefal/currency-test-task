<?php

namespace App\Controllers;

use App\Services\ApiService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CurrencyController
{
    private $currencyRateService;

    public function __construct(ApiService $currencyRateService)
    {
        $this->currencyRateService = $currencyRateService;
    }

    public function getAllRates(Request $request, Response $response, $args)
    {
        $rates = $this->currencyRateService->getAllRates();
        $response->getBody()->write(json_encode($rates));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getRate(Request $request, Response $response, $args)
    {
        $currencyCode = strtoupper($args['currency_code']);
        $rate = $this->currencyRateService->getRate($currencyCode);
        if (!$rate) {
            $response->getBody()->write(json_encode(['error' => 'Currency not found']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        $response->getBody()->write(json_encode($rate));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
