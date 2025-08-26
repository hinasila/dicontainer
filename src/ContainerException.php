<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Exception;
use Psr\Container\ContainerExceptionInterface;

final class ContainerException extends Exception implements ContainerExceptionInterface
{
}
