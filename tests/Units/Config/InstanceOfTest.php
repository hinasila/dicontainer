<?php declare(strict_types=1);

use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiRuleList;
use Tests\Data\ClassInstanceOf;
use Tests\Data\ImplementInterfaceInstanceOf;
use Tests\Data\InterfaceInstanceOf;
use Tests\Data\OverrideClassInstanceOf;
use Tests\Units\Config\AbstractConfigTestCase;

final class InstanceOfTest extends AbstractConfigTestCase
{
    public function testOverrideInterface()
    {
        $obj = $this->dic->get(InterfaceInstanceOf::class);

        $this->assertInstanceOf(ImplementInterfaceInstanceOf::class, $obj);
    }

    public function testOverrideClass()
    {
        $obj = $this->dic->get(ClassInstanceOf::class);

        $this->assertInstanceOf(OverrideClassInstanceOf::class, $obj);
    }

    public function testPassObjectAsInstanceOf()
    {
        $passedObj          = new ImplementInterfaceInstanceOf();
        $rule['instanceOf'] = $passedObj;

        $rlist = new DiRuleList();
        $rlist = $this->rlist->addRule(InterfaceInstanceOf::class, $rule);
        $dic   = new DiContainer($rlist);

        $obj = $dic->get(InterfaceInstanceOf::class);

        $this->assertSame($passedObj, $obj);
    }
}
