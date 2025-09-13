<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Fixtures\ClassGraph;
use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use PHPUnit\Framework\TestCase;
use stdClass;

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

    public function test_transient_instance(): void
    {
        $builder = new DiContainerBuilder();
        $builder->newRule(stdClass::class)
            ->asTransient();

        $dic = $builder->createContainer();

        $objA = $dic->get(stdClass::class);
        $objB = $dic->get(stdClass::class);

        $this->assertNotSame($objA, $objB);
    }
}
