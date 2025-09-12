<?php declare(strict_types=1);

namespace Tests\Features\Basic;

use Tests\DicTestCase;
use Tests\Fixtures\NoConfig\ClassGraph;
use Tests\Fixtures\NoConfig\NullableObject;
use Tests\Fixtures\NoConfig\ObjectDefaultValue;
use Tests\Fixtures\NoConfig\ScalarDefaultValue;
use Tests\Fixtures\NoConfig\ScalarNullable;

final class NoConfigTest extends DicTestCase
{
    public function test_object_tree(): void
    {
        $graph = $this->dic->get(ClassGraph::class);

        $this->assertInstanceOf(ClassGraph::class, $graph); // @phpstan-ignore method.alreadyNarrowedType
        $this->assertInstanceOf('Tests\Fixtures\NoConfig\B', $graph->b);
        $this->assertInstanceOf('Tests\Fixtures\NoConfig\C', $graph->b->c);
        $this->assertInstanceOf('Tests\Fixtures\NoConfig\D', $graph->b->c->d);
        $this->assertInstanceOf('Tests\Fixtures\NoConfig\E', $graph->b->c->e);
        $this->assertInstanceOf('Tests\Fixtures\NoConfig\F', $graph->b->c->e->f);
    }

    public function test_nullable_object(): void
    {
        $obj = $this->dic->get(NullableObject::class);

        $this->assertInstanceOf(NullableObject::class, $obj); // @phpstan-ignore method.alreadyNarrowedType
        $this->assertNull($obj->std);
    }

    public function test_object_default_value(): void
    {
        $obj = $this->dic->get(ObjectDefaultValue::class);

        $this->assertInstanceOf(ObjectDefaultValue::class, $obj); // @phpstan-ignore method.alreadyNarrowedType
        $this->assertNull($obj->obj);
    }

    public function test_scalar_default_value(): void
    {
        $obj = $this->dic->get(ScalarDefaultValue::class);

        $this->assertInstanceOf(ScalarDefaultValue::class, $obj); // @phpstan-ignore method.alreadyNarrowedType
        $this->assertFalse($obj->bool);
        $this->assertSame('Default value', $obj->string);
        $this->assertSame(6, $obj->int);
        $this->assertSame(3.14, $obj->float);
        $this->assertSame([], $obj->emptyArray);
        $this->assertSame([false, true], $obj->boolArray);
        $this->assertSame(['default', 'value'], $obj->stringArray);
        $this->assertSame([6, 11, 7], $obj->intArray);
        $this->assertSame([3.14, 3.8], $obj->floatArray);
    }

    public function test_scalar_nullable(): void
    {
        $obj = $this->dic->get(ScalarNullable::class);

        $this->assertInstanceOf(ScalarNullable::class, $obj); // @phpstan-ignore method.alreadyNarrowedType
        $this->assertNull($obj->bool);
        $this->assertNull($obj->string);
        $this->assertNull($obj->int);
        $this->assertNull($obj->float);
        $this->assertNull($obj->array);
    }
}
