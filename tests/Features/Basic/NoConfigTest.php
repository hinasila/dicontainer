<?php declare(strict_types=1);

namespace Tests\Features\Basic;

use Fixtures\ClassGraph;
use Fixtures\NullableObject;
use Fixtures\NullableSubtitution;
use Fixtures\ObjectDefaultValue;
use Fixtures\ScalarDefaultValue;
use Fixtures\ScalarNullable;
use Hinasila\DiContainer\DiContainer;
use Tests\DicTestCase;

final class NoConfigTest extends DicTestCase
{
    public function test_object_tree(): void
    {
        $graph = $this->dic->get(ClassGraph::class);

        $this->assertInstanceOf(ClassGraph::class, $graph); // @phpstan-ignore method.alreadyNarrowedType
        $this->assertInstanceOf('Fixtures\B', $graph->b);
        $this->assertInstanceOf('Fixtures\C', $graph->b->c);
        $this->assertInstanceOf('Fixtures\D', $graph->b->c->d);
        $this->assertInstanceOf('Fixtures\E', $graph->b->c->e);
        $this->assertInstanceOf('Fixtures\F', $graph->b->c->e->f);
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

    public function test_nullable(): void
    {
        $dic = new DiContainer();

        $instance = $dic->get(NullableSubtitution::class);

        $this->assertNull($instance->obj);
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
