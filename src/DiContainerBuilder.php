<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Closure;
use Hinasila\DiContainer\Rule\InjectRule;
use Hinasila\DiContainer\Rule\RuleBuilder;
use Psr\Container\ContainerInterface;

final class DiContainerBuilder
{
    /**
     * @var array<string,InjectRule>
     */
    private $rules = [];

    /**
     * @return DiContainer
     */
    public function createContainer(): ContainerInterface
    {
        return new DiContainer($this->rules);
    }

    /**
     * @param class-string $serviceId
     * @param Closure(RuleBuilder): void $callback
     */
    public function addRule(string $serviceId, Closure $callback): self
    {
        $builder = $this->getRuleBuilder($serviceId);
        $callback($builder);

        $this->rules[$serviceId] = $builder->getRule();

        return $this;
    }

    private function getRuleBuilder(string $serviceId): RuleBuilder
    {
        $rule = $this->rules[$serviceId] ?? null;

        return new RuleBuilder($serviceId, $rule);
    }

    // public function asTransient(string $key): self
    // {
    //     $this->rules->addRule($key, ['shared' => false]);
    //     return $this;
    // }

    // public function asSingleton(string $key): self
    // {
    //     $this->rules->addRule($key, ['shared' => true]);
    //     return $this;
    // }

    // public function bind(string $interface, string $class): self
    // {
    //     $this->rules->addRule($interface, ['instanceOf' => $class]);
    //     return $this;
    // }

    // /**
    //  * @param mixed[] $params
    //  */
    // public function bindParams(string $service, array $params): self
    // {
    //     $this->rules->addRule($service, ['constructParams' => $params]);
    //     return $this;
    // }

    // public static function init(): self
    // {
    //     return new self();
    // }
}
