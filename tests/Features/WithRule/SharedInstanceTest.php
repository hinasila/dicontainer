<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\NoConfig\ClassGraph;

final class SharedInstanceTest extends TestCase
{
    /**
     * Default behavior is to share the same instance for each call
     */
    public function test_default_shared_behavior(): void
    {
        $dic = new DiContainer();

        $objA = $dic->get(ClassGraph::class);
        $objB = $dic->get(ClassGraph::class);

        $this->assertSame($objA, $objB);
        $this->assertSame($objA->b->c->e, $objB->b->c->e);

        $objB->b->c->e->f = null;
        $this->assertNull($objA->b->c->e->f);
    }

    public function test_shared_instance(): void
    {
        $dic = DiContainerBuilder::init()
            ->asSingleton(stdClass::class)
            ->createContainer();

        $objA = $dic->get(stdClass::class);
        $objB = $dic->get(stdClass::class);

        $this->assertSame($objA, $objB);
    }

    public function test_transient_instance(): void
    {
        $dic = DiContainerBuilder::init()
            ->asTransient(stdClass::class)
            ->createContainer();

        $objA = $dic->get(stdClass::class);
        $objB = $dic->get(stdClass::class);

        $this->assertNotSame($objA, $objB);
    }
}
