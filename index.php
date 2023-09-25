<?php declare(strict_types=1);

require_once __DIR__ . '/src/Container.php';
require_once __DIR__ . '/src/Route.php';
require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/Controllers/UserController.php';
// ... any other required files

$container = new Container();
$container->bind(LoggerInterface::class, FileLogger::class);

$router = new Router($container);

// Autoregister controllers
$router->autoRegisterControllers(__DIR__ . '/src/Controllers');

// Capture request URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Dispatch the request
$router->dispatch($requestUri, $requestMethod);
