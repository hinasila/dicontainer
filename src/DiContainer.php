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

/**
 * @template T as object
 */
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

        $list       = $list ?? new DiRuleList();
        $this->list = $list->addRule(ContainerInterface::class, [
          'instanceOf' => DiContainer::class,
        ]);
    }

    /**
     * @param class-string<T> $id
     */
    public function has(string $id): bool
    {
        return $this->list->hasRule($id) || \class_exists($id);
    }

    /**
     * @param class-string<T> $id
     * return T
     */
    public function get(string $id)
    {
        if ($this->has($id) === false) {
            throw new NotFoundException(\sprintf('Service "%s" does not exist', $id));
        }

        $rule = $this->list->getRule($id);
        if ($rule->classname() === __CLASS__) {
            return clone $this;
        }

        if (empty($rule->getFrom()) === true) {
            return $this->getInstance($rule);
        }

        $getFrom  = $rule->getFrom();
        $callback = \array_shift($getFrom);
        $args     = \array_shift($getFrom);

        $callback = $this->callback->toCallback($callback);
        return \call_user_func_array($callback, (array) $args);
    }

    /**
     * @return T
     */
    private function getInstance(DiRule $rule)
    {
        if (isset($this->instances[$rule->key()]) === true) {
            return $this->instances[$rule->key()];
        }

        if (
            \array_key_exists($rule->key(), $this->curKeys) === true
            || \in_array($rule->classname(), $this->curKeys) === true
        ) {
            throw new ContainerException('Cyclic dependencies detected');
        }

        $classname = $rule->classname();
        if (\is_object($classname) === true) {
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

        if ($rule->isShared() === true) {
            $this->instances[$rule->key()] = $object;
        }

        return $object;
    }

    /**
     * @return object
     */
    private function createObject(DiRule $rule)
    {
        $ref = new ReflectionClass($rule->classname());
        if ($ref->isAbstract() === true) {
            throw new ContainerException('Cannot instantiate abstract class ' . $rule->classname());
        }

        $params = $this->parser->parse($ref->getConstructor(), $rule->params(), $rule->substitutions());
        return $this->getObject($ref, $params);
    }

    /**
     * @param ReflectionClass<Object> $ref
     * @param array<mixed> $params
     *
     * @return object
     */
    private function getObject(ReflectionClass $ref, array $params)
    {
        try {
            return $ref->newInstanceArgs($params);
        } catch (Error $exc) {
            throw new ContainerException($exc->getMessage(), 1, $exc);
        }
    }
}
