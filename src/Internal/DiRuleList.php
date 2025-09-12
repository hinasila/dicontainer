<?php declare(strict_types=1);

namespace Hinasila\DiContainer\Internal;

/**
 * @internal
 */
final class DiRuleList
{
    /**
     * @var array<string,DiRule>
     */
    private $rules = [];

    /** @param array<string,mixed> $rule */
    public function addRule(string $key, array $rule): self
    {
        $this->addToList(new DiRule($key, $rule));
        return $this;
    }

    /** @param array<string,array<string,mixed>> $rules */
    public function addRules(array $rules): self
    {
        foreach ($rules as $key => $values) {
            $this->addToList(new DiRule($key, $values));
        }

        return $this;
    }

    public function hasRule(string $key): bool
    {
        return \array_key_exists($key, $this->rules);
    }

    public function getRule(string $key): DiRule
    {
        if ($this->hasRule($key)) {
            return $this->rules[$key];
        }

        $rule = new DiRule($key, []);
        $this->addToList($rule);
        return $rule;
    }

    private function addToList(DiRule $rule): void
    {
        if (\array_key_exists($rule->key(), $this->rules)) {
            $oldRule = $this->rules[$rule->key()];
            $oldRule->cloneFrom($rule);
            $rule = $oldRule;
        }
        $this->rules[$rule->key()] = $rule;
    }
}
