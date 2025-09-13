<?php declare(strict_types=1);

namespace Tests\Features\Basic;

use ArrayAccess;
use Fixtures\AbstractClass;
use Fixtures\PrivateConstructor;
use Fixtures\ProtectedConstructor;
use Fixtures\TraitTest;
use Hinasila\DiContainer\Exception\ContainerException;
use Hinasila\DiContainer\Exception\NotFoundException;
use Tests\DicTestCase;

final class InvalidCaseTest extends DicTestCase
{
    public function test_create_interface(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(\sprintf('Service "%s" does not exist', ArrayAccess::class));

        $this->dic->get(ArrayAccess::class);
    }

    public function test_create_trait(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(\sprintf('Service "%s" does not exist', TraitTest::class));


        $this->dic->get(TraitTest::class);
    }

    public function test_create_abstract_class(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cannot instantiate abstract class ' . AbstractClass::class);

        $this->dic->get(AbstractClass::class);
    }

    public function test_create_private_constructor(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . PrivateConstructor::class);

        $this->dic->get(PrivateConstructor::class);
    }

    public function test_protected_constructor(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . ProtectedConstructor::class);

        $this->dic->get(ProtectedConstructor::class);
    }
}
