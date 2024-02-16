<?php

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\CurrencyController;
use App\Models\CurrencyRate;
use App\Services\ApiService;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$twig = Twig::create(__DIR__ . '/templates');

$containerBuilder->addDefinitions([
    PDO::class => function ($container) {
        $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'] . ';charset=utf8mb4';
        return new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    },
    CurrencyRate::class => static function ($container) {
        return new CurrencyRate($container->get(PDO::class));
    },
    ApiService::class => static function ($container) {
        return new ApiService($container->get(CurrencyRate::class));
    },
    'view' => static function () use ($twig) {
        return $twig;
    },
]);
$container = $containerBuilder->build();

$app = AppFactory::createFromContainer($container);

$app->addErrorMiddleware(true, true, true);

$app->get('/rates', CurrencyController::class . ':getAllRates');
$app->get('/rate/{currency_code}', CurrencyController::class . ':getRate');

$app->get('/', function (Request $request, Response $response) {
    $readmeContent = file_get_contents('README.md');
    $parser = new Parsedown();
    $htmlContent = $parser->text($readmeContent);
    return $this->get('view')->render($response, 'index.twig', ['content' => $htmlContent]);
});

$app->run();