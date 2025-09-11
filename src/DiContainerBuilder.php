<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

final class DiContainerBuilder
{
    public function __construct()
    {
        // Do nothing
    }

    public function createContainer(): DiContainer
    {
        return new DiContainer();
    }

    public static function init(): self
    {
        return new self();
    }
}
