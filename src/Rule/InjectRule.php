<?php declare(strict_types=1);

namespace Hinasila\DiContainer\Rule;

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
    private $resolveAs;

    /**
     * @var bool
     */
    private $shared;

    /**
     * @var array<mixed>
     */
    private $params;

    /**
     * @var array<string,string>
     */
    private $wireArgs;

    /**
     * @param array<mixed> $params
     * @param array<string,string> $wireArgs
     */
    public function __construct(string $serviceId, ?string $resolveAs, array $params, array $wireArgs, bool $shared = true)
    {
        $this->serviceId = $serviceId;
        $this->resolveAs = $resolveAs;
        $this->params    = $params;
        $this->wireArgs  = $wireArgs;
        $this->shared    = $shared;
    }

    public function serviceId(): string
    {
        return $this->serviceId;
    }

    public function classname(): string
    {
        return $this->resolveAs ?: $this->serviceId;
    }

    public function resolveAs(): ?string
    {
        return $this->resolveAs;
    }

    public function isShared(): bool
    {
        return $this->shared;
    }

    /**
     * @return array<mixed>
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @return array<mixed>
     */
    public function wireArgs(): array
    {
        return $this->wireArgs;
    }

    /**
     * @return array<mixed>
     */
    public function getFrom(): array
    {
        return [];
    }
}
