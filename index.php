<?php declare(strict_types=1);

require_once __DIR__ . '/src/Container.php';
require_once __DIR__ . '/src/Route.php';
require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/Controllers/UserController.php';
// ... any other required files

// Initialize the container and add the logger implementation
$container = new Container();

$container->bind(LoggerInterface::class, FileLogger::class);

// Initialize the router
$router = new Router($container);

// Autoregister controllers
$router->autoRegisterControllers(__DIR__ . '/src/Controllers');

// Dispatch the request
$router->dispatch();
