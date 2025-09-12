<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Hinasila\DiContainer\Internal\DiRuleList;
use Psr\Container\ContainerInterface;

final class DiContainerBuilder
{
    private $rules;

    public function __construct()
    {
        $this->rules = new DiRuleList();
    }

    /**
     * @return DiContainer
     */
    public function createContainer(): ContainerInterface
    {
        return new DiContainer($this->rules);
    }

    public function asTransient(string $key): self
    {
        $this->rules = $this->rules->addRule($key, ['shared' => false]);
        return $this;
    }

    public function asSingleton(string $key): self
    {
        $this->rules = $this->rules->addRule($key, ['shared' => true]);
        return $this;
    }

    public function bind(string $interface, string $class): self
    {
        $this->rules = $this->rules->addRule($interface, ['instanceOf' => $class]);
        return $this;
    }

    /**
     * @param mixed[] $params
     */
    public function bindParams(string $service, array $params): self
    {
        $this->rules = $this->rules->addRule($service, ['constructParams' => $params]);
        return $this;
    }

    public static function init(): self
    {
        return new self();
    }
}
