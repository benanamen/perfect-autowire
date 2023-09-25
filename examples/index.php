<?php declare(strict_types=1);

use App\Controllers\LoggerInterface;
use PerfectApp\Container\Container;
use PerfectApp\Routing\Router;

require '../vendor/autoload.php';
require 'Controllers/LoggerInterface.php';

$container = new Container();
$container->bind(LoggerInterface::class, FileLogger::class);

$router = new Router($container);
$router->autoRegisterControllers('./Controllers');
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
$requestMethod = $_SERVER['REQUEST_METHOD'];
$router->dispatch($requestUri, $requestMethod);
