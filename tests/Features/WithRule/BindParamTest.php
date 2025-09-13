<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Fixtures\ClassArray;
use Fixtures\ClassBool;
use Fixtures\ClassFloat;
use Fixtures\ClassInt;
use Fixtures\ClassString;
use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use Hinasila\DiContainer\Exception\ContainerException;
use PHPUnit\Framework\TestCase;

final class BindParamTest extends TestCase
{
    public function test_required_param(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'required',
                ClassString::class
            )
        );

        $dic = new DiContainer();
        $dic->get(ClassString::class);
    }

    public function test_invalid_type(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            \sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'required',
                ClassString::class
            )
        );

        $dic = (new DiContainerBuilder())
            ->map(ClassString::class, null, [123])
            ->createContainer();

        $dic->get(ClassString::class);
    }

    public function test_string(): void
    {
        $dic = (new DiContainerBuilder())
            ->map(ClassString::class, null, ['123', null])
            ->createContainer();

        $instance = $dic->get(ClassString::class);

        $this->assertSame('123', $instance->required);
        $this->assertSame('Optional', $instance->optional);
        $this->assertNull($instance->null);
    }

    public function test_bool(): void
    {
        $dic = (new DiContainerBuilder())
            ->map(ClassBool::class, null, [true])
            ->createContainer();

        $instance = $dic->get(ClassBool::class);

        $this->assertTrue($instance->required);
        $this->assertTrue($instance->optional);
        $this->assertNull($instance->null);
    }

    public function test_int(): void
    {
        $dic = (new DiContainerBuilder())
            ->map(ClassInt::class, null, [2025])
            ->createContainer();

        $instance = $dic->get(ClassInt::class);

        $this->assertSame(2025, $instance->required);
        $this->assertSame(2019, $instance->optional);
        $this->assertNull($instance->null);
    }

    public function test_float(): void
    {
        $dic = (new DiContainerBuilder())
            ->map(ClassFloat::class, null, [1e9])
            ->createContainer();

        $instance = $dic->get(ClassFloat::class);

        $this->assertSame(1e9, $instance->required);
        $this->assertSame(3.14, $instance->optional);
        $this->assertNull($instance->null);
    }

    public function test_array(): void
    {
        $dic = (new DiContainerBuilder())
            ->map(ClassArray::class, null, [[1e9]])
            ->createContainer();

        $instance = $dic->get(ClassArray::class);

        $this->assertSame([1e9], $instance->required);
        $this->assertSame([3.14], $instance->optional);
        $this->assertNull($instance->null);
    }
}
