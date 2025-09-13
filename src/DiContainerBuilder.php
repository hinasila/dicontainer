<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Hinasila\DiContainer\Rule\InjectRule;
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
     */
    public function map(string $serviceId, ?string $mapTo = null): InjectRule
    {
        $this->rules[$serviceId] = $this->rules[$serviceId] ?? new InjectRule($serviceId, $mapTo);

        return $this->rules[$serviceId];
    }

    // /**
    //  * @param class-string $serviceId
    //  * @param Closure(RuleBuilder): void $callback
    //  */
    // public function addRule(string $serviceId, Closure $callback): self
    // {
    //     $builder = $this->getRuleBuilder($serviceId);
    //     $callback($builder);

    //     $this->rules[$serviceId] = $builder->getRule();

    //     return $this;
    // }

    // private function getRuleBuilder(string $serviceId): RuleBuilder
    // {
    //     $rule = $this->rules[$serviceId] ?? null;

    //     return new RuleBuilder($serviceId, $rule);
    // }
}
