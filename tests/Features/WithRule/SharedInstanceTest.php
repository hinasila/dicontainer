<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\NoConfig\ClassGraph;
use Tests\Fixtures\NoConfig\E;

final class SharedInstanceTest extends TestCase
{
    /**
     * Default behavior is to create a new instance for each call
     */
    public function test_default_behavior(): void
    {
        $dic = new DiContainer();

        $objA = $dic->get(ClassGraph::class);
        $objB = $dic->get(ClassGraph::class);

        $this->assertNotSame($objA, $objB);
        $this->assertNotSame($objA->b->c->e, $objB->b->c->e);

        $objB->b->c->e->f = null;
        $this->assertNotNull($objA->b->c->e->f);
    }

    public function test_shared_instance(): void
    {
        $dic = DiContainerBuilder::init()
            ->configureSingleton(stdClass::class)
            ->createContainer();

        $objA = $dic->get(stdClass::class);
        $objB = $dic->get(stdClass::class);

        $this->assertSame($objA, $objB);
    }

    public function test_shared_dependency(): void
    {
        $dic = DiContainerBuilder::init()
            ->configureSingleton(E::class)
            ->createContainer();

        $objA = $dic->get(ClassGraph::class);
        $objB = $dic->get(ClassGraph::class);

        $this->assertNotSame($objA, $objB);
        $this->assertNotSame($objA->b->c, $objB->b->c);
        $this->assertSame($objA->b->c->e, $objB->b->c->e);

        $this->assertNotNull($objA->b->c->e->f);

        $objB->b->c->e->f = null;
        $this->assertNull($objA->b->c->e->f);
    }
}
