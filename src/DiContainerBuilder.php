<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Hinasila\DiContainer\Internal\InjectRule;
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
}
