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

    public function createContainer(): ContainerInterface
    {
        return new DiContainer($this->rules);
    }

    public function configureSingleton(string $key): self
    {
        $this->rules = $this->rules->addRule($key, ['shared' => true]);
        return $this;
    }

    public static function init(): self
    {
        return new self();
    }
}
