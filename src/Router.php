<?php declare(strict_types=1);

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
            throw new Exception("The directory {$directory} does not exist");
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
        $reflectionClass = new ReflectionClass($controllerName);
        foreach ($reflectionClass->getMethods() as $method) {
            $routeAttribute = $method->getAttributes(Route::class)[0] ?? null;
            if ($routeAttribute) {
                $routeData = $routeAttribute->newInstance();
                $this->routes[$routeData->path] = [
                    'controller' => $controllerName,
                    'method' => $method->getName()
                ];
            }
        }
    }

    public function dispatch(): void
    {
        // Capture the actual request URI from the server superglobal
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$requestUri])) {
            $controllerName = $this->routes[$requestUri]['controller'];
            $methodName = $this->routes[$requestUri]['method'];

            $controller = $this->container->get($controllerName);
            $controller->$methodName();
        } else {
            echo "Route {$requestUri} not found.\n";
        }
    }
}
