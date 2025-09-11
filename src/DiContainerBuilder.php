<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Psr\Container\ContainerInterface;

final class DiContainerBuilder
{
    public function __construct()
    {
        // Do nothing
    }

    public function createContainer(): ContainerInterface
    {
        return new DiContainer();
    }

    public static function init(): self
    {
        return new self();
    }
}
