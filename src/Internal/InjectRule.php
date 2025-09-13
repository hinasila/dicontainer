<?php declare(strict_types=1);

namespace Hinasila\DiContainer\Internal;

use Closure;

/**
 * @internal
 */
final class InjectRule
{
    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var string|null
     */
    private $mapTo;

    /**
     * @var bool
     */
    private $shared = true;

    /**
     * @var array<mixed>
     */
    private $params = [];

    /**
     * @var array<string,string>
     */
    private $bindArgs = [];

    /**
     * @var Closure|null
     */
    private $closure;

    public function __construct(string $serviceId, ?string $mapTo = null)
    {
        $this->serviceId = $serviceId;
        $this->mapTo     = $mapTo;
    }

    public function serviceId(): string
    {
        return $this->serviceId;
    }

    public function classname(): string
    {
        return $this->mapTo ?: $this->serviceId;
    }

    public function isShared(): bool
    {
        return $this->shared;
    }

    public function asTransient(): self
    {
        $this->shared = false;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array<mixed> $params
     */
    public function bindParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getBindArgs(): array
    {
        return $this->bindArgs;
    }

    public function bindArg(string $interface, string $class): self
    {
        $this->bindArgs[$interface] = $class;
        return $this;
    }

    public function getClosure(): ?Closure
    {
        return $this->closure;
    }

    public function useClosure(Closure $closure): self
    {
        $this->closure = $closure;
        return $this;
    }
}
