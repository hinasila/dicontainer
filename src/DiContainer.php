<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Closure;
use Error;
use Hinasila\DiContainer\Exception\ContainerException;
use Hinasila\DiContainer\Exception\NotFoundException;
use Hinasila\DiContainer\Internal\CallbackHelper;
use Hinasila\DiContainer\Internal\DiParser;
use Hinasila\DiContainer\Internal\InjectRule;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

final class DiContainer implements ContainerInterface
{
    /**
     * @var array<string,InjectRule>
     */
    private $rules;

    private $callback;

    private $parser;

    /**
     * @var object[]
     */
    private $instances = [];

    /**
     * @var array<string,string>
     */
    private $curKeys = [];

    /**
     * @param array<string,InjectRule> $rules
     */
    public function __construct(array $rules = [])
    {
        $this->callback = new CallbackHelper($this);
        $this->parser   = new DiParser([$this, 'get'], $this->callback);

        $rules[ContainerInterface::class] = new InjectRule(ContainerInterface::class, self::class);

        $this->rules = $rules;
    }

    /**
     * @param class-string $serviceId
     */
    public function has(string $serviceId): bool
    {
        return \array_key_exists($serviceId, $this->rules) || \class_exists($serviceId);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $serviceId
     * @return T
     */
    public function get(string $serviceId)
    {
        if ($this->has($serviceId) === false) {
            throw new NotFoundException(\sprintf('Service "%s" does not exist', $serviceId));
        }

        $rule = $this->getRule($serviceId);
        if ($rule->classname() === self::class) {
            return clone $this;
        }


        $closure = $rule->getClosure();
        if ($closure instanceof Closure) {
            return $closure();
        }
        return $this->getInstance($rule);


        // $getFrom = $rule->getFrom();
        // if ($getFrom === []) {
        //     return $this->getInstance($rule);
        // }

        // $callback = \array_shift($getFrom);
        // $args     = \array_shift($getFrom);

        // $callback = $this->callback->toCallback($callback);
        // return \call_user_func_array($callback, (array) $args);
    }

    /**
     * @return object
     */
    private function getInstance(InjectRule $rule)
    {
        if (isset($this->instances[$rule->serviceId()])) {
            return $this->instances[$rule->serviceId()];
        }

        if (
            \array_key_exists($rule->serviceId(), $this->curKeys)
            || \in_array($rule->classname(), $this->curKeys)
        ) {
            throw new ContainerException('Cyclic dependencies detected');
        }

        $classname = $rule->classname();
        // if (\is_object($classname)) {
        //     return $classname;
        // }
        $this->curKeys[$rule->serviceId()] = $classname;

        try {
            $object = $this->createObject($rule);
            unset($this->curKeys[$rule->serviceId()]);
        } catch (Throwable $exc) {
            unset($this->curKeys[$rule->serviceId()]);
            throw $exc;
        }

        if ($rule->isShared()) {
            $this->instances[$rule->serviceId()] = $object;
        }

        return $object;
    }

    private function createObject(InjectRule $rule): object
    {
        $ref = new ReflectionClass($rule->classname());
        if ($ref->isAbstract()) {
            throw new ContainerException('Cannot instantiate abstract class ' . $rule->classname());
        }

        $params = $this->parser->parse($ref->getConstructor(), $rule->getParams(), $rule->getBindArgs());
        return $this->getObject($ref, $params);
    }

    /**
     * @param ReflectionClass<Object> $ref
     * @param array<mixed> $params
     */
    private function getObject(ReflectionClass $ref, array $params): object
    {
        try {
            return $ref->newInstanceArgs($params);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
    }

    private function getRule(string $serviceId): InjectRule
    {
        if (!isset($this->rules[$serviceId])) {
            return new InjectRule($serviceId);
        }

        return $this->rules[$serviceId];
    }
}
