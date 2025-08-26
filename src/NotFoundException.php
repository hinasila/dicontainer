<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}
