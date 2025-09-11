<?php declare(strict_types=1);

namespace Hinasila\DiContainer\Exception;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
