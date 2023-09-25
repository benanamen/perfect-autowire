<?php declare(strict_types=1);

namespace PerfectApp\Container;

use ReflectionClass;

class Container
{
    private array $instances = [];
    private array $bindings = [];

    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function get(string $className): object
    {
        if (isset($this->bindings[$className])) {
            $className = $this->bindings[$className];
        }

        if (!isset($this->instances[$className])) {
            $reflectionClass = new ReflectionClass($className);
            $constructor = $reflectionClass->getConstructor();
            if ($constructor) {
                $params = $constructor->getParameters();
                $dependencies = [];
                foreach ($params as $param) {
                    $type = $param->getType();
                    if ($type && !$type->isBuiltin()) {
                        $dependency = $type->getName();
                        $dependencies[] = $this->get($dependency);
                    }
                }
                $this->instances[$className] = $reflectionClass->newInstanceArgs($dependencies);
            } else {
                $this->instances[$className] = new $className();
            }
        }
        return $this->instances[$className];
    }
}
