<?php declare(strict_types=1);

namespace Tests\Features\Basic;

use Fixtures\CyclicA;
use Hinasila\DiContainer\Exception\ContainerException;
use Tests\DicTestCase;

final class CyclicTest extends DicTestCase
{
    public function test_cyclic_dependencies(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cyclic dependencies detected');

        $this->dic->get(CyclicA::class);
    }
}
