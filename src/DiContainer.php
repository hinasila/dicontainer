<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Error;
use Hinasila\DiContainer\Exception\ContainerException;
use Hinasila\DiContainer\Exception\NotFoundException;
use Hinasila\DiContainer\Internal\CallbackHelper;
use Hinasila\DiContainer\Internal\DiParser;
use Hinasila\DiContainer\Internal\DiRule;
use Hinasila\DiContainer\Internal\DiRuleList;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

final class DiContainer implements ContainerInterface
{
    private $list;

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

    public function __construct(?DiRuleList $list = null)
    {
        $this->callback = new CallbackHelper($this);
        $this->parser   = new DiParser([$this, 'get'], $this->callback);

        $this->list = $list ?? new DiRuleList();
        $this->list->addRule(ContainerInterface::class, [
          'instanceOf' => DiContainer::class,
        ]);
    }

    /**
     * @param class-string $id
     */
    public function has(string $id): bool
    {
        return $this->list->hasRule($id) || \class_exists($id);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(\sprintf('Service "%s" does not exist', $id));
        }

        $rule = $this->list->getRule($id);
        if ($rule->classname() === self::class) {
            return clone $this;
        }

        $getFrom = $rule->getFrom();
        if ($getFrom === []) {
            return $this->getInstance($rule);
        }

        $callback = \array_shift($getFrom);
        $args     = \array_shift($getFrom);

        $callback = $this->callback->toCallback($callback);
        return \call_user_func_array($callback, (array) $args);
    }

    /**
     * @return object
     */
    private function getInstance(DiRule $rule)
    {
        if (isset($this->instances[$rule->key()])) {
            return $this->instances[$rule->key()];
        }

        if (
            \array_key_exists($rule->key(), $this->curKeys)
            || \in_array($rule->classname(), $this->curKeys)
        ) {
            throw new ContainerException('Cyclic dependencies detected');
        }

        $classname = $rule->classname();
        if (\is_object($classname)) {
            return $classname;
        }
        $this->curKeys[$rule->key()] = $classname;

        try {
            $object = $this->createObject($rule);
            unset($this->curKeys[$rule->key()]);
        } catch (Throwable $exc) {
            unset($this->curKeys[$rule->key()]);
            throw $exc;
        }

        if ($rule->isShared()) {
            $this->instances[$rule->key()] = $object;
        }

        return $object;
    }

    private function createObject(DiRule $rule): object
    {
        $ref = new ReflectionClass($rule->classname());
        if ($ref->isAbstract()) {
            throw new ContainerException('Cannot instantiate abstract class ' . $rule->classname());
        }

        $params = $this->parser->parse($ref->getConstructor(), $rule->params(), $rule->substitutions());
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
}
