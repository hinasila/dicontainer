<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

final class DiRuleList
{
    /**
     * @var array<string,DiRule>
     */
    private $rules = [];

    /** @param array<string,mixed> $rule */
    public function addRule(string $key, array $rule): self
    {
        $new = clone $this;
        $this->addToList($new, new DiRule($key, $rule));
        return $new;
    }

    /** @param array<string,array<string,mixed>> $rules */
    public function addRules(array $rules): self
    {
        $new = clone $this;

        foreach ($rules as $key => $values) {
            $this->addToList($new, new DiRule($key, $values));
        }

        return $new;
    }

    public function hasRule(string $key): bool
    {
        return \array_key_exists($key, $this->rules);
    }

    public function getRule(string $key): DiRule
    {
        if ($this->hasRule($key) === true) {
            return $this->rules[$key];
        }

        $rule = new DiRule($key, []);
        $this->addToList($this, $rule);
        return $rule;
    }

    private function addToList(DiRuleList $list, DiRule $rule): void
    {
        if (\array_key_exists($rule->key(), $list->rules) === true) {
            $oldRule = $list->rules[$rule->key()];
            $oldRule->cloneFrom($rule);
            $rule = $oldRule;
        }
        $list->rules[$rule->key()] = $rule;
    }
}
