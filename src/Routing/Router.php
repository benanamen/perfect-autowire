<?php declare(strict_types=1);

namespace PerfectApp\Routing;

use PerfectApp\Container\Container;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;

class Router
{
    private array $routes = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function autoRegisterControllers(string $directory): void
    {
        if (!is_dir($directory)) {
            error_log("The directory $directory does not exist");
            http_response_code(500);
            die('Fatal Error. See Error log for details.');
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($files as $file) {

            if ($file->isDir() || $file->getFilename()[0] === '.' || $file->getExtension() !== 'php') {
                continue;
            }

            require_once $file->getPathname();
            $className = basename($file->getPathname(), '.php');

            if (class_exists($className)) {
                $this->registerController($className);
            }
        }
    }

    public function registerController(string $controllerName): void
    {
        try {
            $reflectionClass = new ReflectionClass($controllerName);
        } catch (ReflectionException $e) {
            error_log("Failed to create Controller ReflectionClass for $controllerName: {$e->getMessage()}");
            http_response_code(500);
            die('Fatal Error. See Error log for details.');
        }

        foreach ($reflectionClass->getMethods() as $method) {
            $routeAttributes = $method->getAttributes(Route::class);
            foreach ($routeAttributes as $routeAttribute) {
                $routeData = $routeAttribute->newInstance();
                $this->routes[] = [
                    'path' => $routeData->path,
                    'methods' => $routeData->methods,
                    'controller' => $controllerName,
                    'action' => $method->getName(),
                ];
            }
        }
    }

    public function dispatch(string $requestUri, string $requestMethod): void
    {
        foreach ($this->routes as $routeInfo) {
            $pattern = "@^" . str_replace("/", "\\/", $routeInfo['path']) . "$@";

            if (in_array($requestMethod, $routeInfo['methods']) && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove the entire string that was matched

                $controllerName = $routeInfo['controller'];
                $methodName = $routeInfo['action'];

                $controller = $this->container->get($controllerName);
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }
        echo "PerfectApp\Routing\Route $requestUri with method $requestMethod not found.\n";
    }
}
