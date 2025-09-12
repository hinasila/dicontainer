<?php declare(strict_types=1);

namespace Hinasila\DiContainer\Internal;

use Hinasila\DiContainer\Exception\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionMethod;

/**
 * @internal
 */
final class CallbackHelper
{
    private $dic;

    public function __construct(ContainerInterface $dic)
    {
        $this->dic = $dic;
    }

    /** @param mixed $callback */
    public function toCallback($callback): callable
    {
        try {
            $ref = $this->getReflection($callback);

            if (!$ref instanceof ReflectionMethod) {
                return $callback;
            }

            if ($ref->isStatic() === false) {
                $classname  = $ref->getDeclaringClass()->getName();
                $methodName = $ref->getName();
                $obj        = $this->dic->get($classname);
                $callback   = [$obj, $methodName];
            }

            return $callback;
        } catch (ReflectionException $exc) {
        }

        if (\is_callable($callback)) {
            return $callback;
        }

        throw new ContainerException('getFrom rule is not a callable');
    }

    /** @param mixed $callback */
    private function getReflection($callback): ?ReflectionMethod
    {
        if (\is_string($callback)) {
            return new ReflectionMethod($callback);
        }
        if (\is_array($callback)) {
            return $this->fromArray($callback);
        }
        // $callback is an object
        return null;
    }

    /** @param array{string|object,string} $callback */
    private function fromArray(array $callback): ?ReflectionMethod
    {
        if (\is_object($callback[0])) {
            return null;
        }

        return new ReflectionMethod($callback[0], $callback[1]);
    }
}
