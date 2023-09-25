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
            throw new Exception("The directory $directory does not exist");
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
        echo "Route $requestUri with method $requestMethod not found.\n";
    }
}
