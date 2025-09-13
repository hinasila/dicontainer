<?php declare(strict_types=1);

namespace Hinasila\DiContainer\Rule;

final class RuleBuilder
{
    /**
     * @var InjectRule|null
     */
    private $rule;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var array{shared?:bool,resolveAs?:string,params?:array<mixed>,wireArgs?:array<string,string>}
     */
    private $rules = [];

    public function __construct(string $serviceId, ?InjectRule $rule = null)
    {
        $this->serviceId = $serviceId;
        $this->rule      = $rule;
    }

    public function getRule(): InjectRule
    {
        $shared    = $this->rules['shared'] ?? true;
        $params    = $this->rules['params'] ?? [];
        $wireArgs  = $this->rules['wireArgs'] ?? [];
        $resolveAs = $this->rules['resolveAs'] ?? null;

        if ($this->rule) {
            $shared    = $this->rule->isShared();
            $params    = $this->rule->params();
            $wireArgs  = $this->rule->wireArgs();
            $resolveAs = $this->rule->resolveAs();
        }

        return new InjectRule($this->serviceId, $resolveAs, $params, $wireArgs, $shared);
    }

    public function resolveAs(string $classname): self
    {
        $this->rules['resolveAs'] = $classname;
        return $this;
    }

    public function asSingleton(): self
    {
        $this->rules['shared'] = true;
        return $this;
    }

    public function asTransient(): self
    {
        $this->rules['shared'] = false;
        return $this;
    }

    /**
     * @param array<mixed> $params
     */
    public function bindParams(array $params): self
    {
        $this->rules['params'] = $params;
        return $this;
    }

    public function wireArg(string $interface, string $class): self
    {
        $this->rules['wireArgs'][$interface] = $class;
        return $this;
    }
}
