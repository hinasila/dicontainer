<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

final class DiRule
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var array<string,mixed>
     */
    private $rules;

    /** @param array<string,mixed> $rules */
    public function __construct(string $key, array $rules)
    {
        $this->key   = \ltrim($key, '\\');
        $this->rules = $rules;
    }

    public function key(): string
    {
        return $this->key;
    }

    /** @return class-string|object */
    public function classname()
    {
        /** @var class-string|object $class */
        $class = $this->rules['instanceOf'] ?? $this->key;
        return $class;
    }

    public function isShared(): bool
    {
        return (bool) ($this->rules['shared'] ?? false);
    }

    /** @return array<int,mixed> */
    public function params(): array
    {
        return $this->rules['constructParams'] ?? [];
    }

    /** @return array<string,string> */
    public function substitutions(): array
    {
        return $this->rules['substitutions'] ?? [];
    }

    /** @return array<mixed>*/
    public function getFrom(): array
    {
        return $this->rules['getFrom'] ?? [];
    }

    public function cloneFrom(DiRule $rule): void
    {
        foreach ($rule->rules as $key => $values) {
            switch ($key) {
                case 'instanceOf':
                    $this->rules[$key] = $values;
                    break;
                case 'shared':
                    $this->rules[$key] = $values;
                    break;
                case 'constructParams':
                    $this->rules[$key] = $values;
                    break;
                case 'substitutions':
                    $this->mergeInterface($values);
                    break;
                case 'getFrom':
                    $this->rules[$key] = $values;
                    break;
            }
        }
    }

    /** @param array<string,string> $values */
    private function mergeInterface(array $values): void
    {
        foreach ($values as $key => $class) {
            $this->rules['substitutions'][$key] = $class;
        }
    }
}
