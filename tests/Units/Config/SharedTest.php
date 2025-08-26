<?php declare(strict_types=1);

use Hinasila\DiContainer\DiContainer;
use Tests\Data\ClassGraph;
use Tests\Units\Config\AbstractConfigTestCase;

final class SharedTest extends AbstractConfigTestCase
{
    protected function setUp(): void
    {
        $this->files = ['BasicClass.php'];
        parent::setUp();
    }

    public function testSharedArgument()
    {
        $objA = $this->dic->get(ClassGraph::class);
        $objB = $this->dic->get(ClassGraph::class);

        $this->assertNotSame($objA, $objB);
        $this->assertSame($objA->b->c->e, $objB->b->c->e);
        $this->assertNotNull($objA->b->c->e->f);

        $objB->b->c->e->f = null;
        $this->assertNull($objA->b->c->e->f);
    }

    public function testSharedInstance()
    {
        $objA = $this->dic->get(stdClass::class);
        $objB = $this->dic->get(stdClass::class);

        $this->assertSame($objA, $objB);

        $objA->value = 'Test shared';
        $this->assertSame($objA->value, $objB->value);
    }

    public function testOverrideSharedRule()
    {
        $rlist = $this->rlist->addRule(stdClass::class, ['shared' => false]);
        $dic   = new DiContainer($rlist);

        $objA = $dic->get(stdClass::class);
        $objB = $dic->get(stdClass::class);

        $this->assertNotSame($objA, $objB);
    }
}
