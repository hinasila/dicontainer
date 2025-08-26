<?php declare(strict_types=1);

use Hinasila\DiContainer\ContainerException;
use Hinasila\DiContainer\DiContainer;
use Tests\Data\AllowsNullInterface;
use Tests\Data\ClassNoRule;
use Tests\Data\DefaultValueInterface;
use Tests\Data\DependInterfaceA;
use Tests\Data\DependInvalidSubsInterface;
use Tests\Data\ImplementInterfaceA;
use Tests\Data\ImplInvalidSubsInterface;
use Tests\Data\InterfaceNoRule;
use Tests\Data\InvalidSubsInterface;
use Tests\Units\Config\AbstractConfigTestCase;

final class SubstitutionsTest extends AbstractConfigTestCase
{
    public function testMissingSubstitution()
    {
        $msg = sprintf(
            'Missing required substitutions %s passed to %s::__construct()',
            InterfaceNoRule::class,
            ClassNoRule::class
        );
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ClassNoRule::class);
    }

    public function testSubstitutionRule()
    {
        $obj = $this->dic->get(DependInterfaceA::class);

        $this->assertInstanceOf(ImplementInterfaceA::class, $obj->obj);
    }

    public function testSubsWithDifferentClass()
    {
        $this->expectException(ContainerException::class);

        $this->dic->get(DependInvalidSubsInterface::class);
    }

    public function testOverrideSubstitutionsRule()
    {
        $rule['substitutions'] = [InvalidSubsInterface::class => ImplInvalidSubsInterface::class];
        $rlist                 = $this->rlist->addRule(DependInvalidSubsInterface::class, $rule);
        $dic                   = new DiContainer($rlist);

        $obj = $dic->get(DependInvalidSubsInterface::class);

        $this->assertInstanceOf(ImplInvalidSubsInterface::class, $obj->obj);
    }

    public function testOverrideWithConstructParams()
    {
        $passedObj               = new ImplInvalidSubsInterface();
        $rule['constructParams'] = [$passedObj];
        $rlist                   = $this->rlist->addRule(DependInvalidSubsInterface::class, $rule);
        $dic                     = new DiContainer($rlist);

        $obj = $dic->get(DependInvalidSubsInterface::class);

        $this->assertSame($passedObj, $obj->obj);
    }

    public function testAllowsNullInterface()
    {
        $obj = $this->dic->get(AllowsNullInterface::class);

        $this->assertNull($obj->obj);
    }

    public function testDefaultValueInterface()
    {
        $obj = $this->dic->get(DefaultValueInterface::class);

        $this->assertNull($obj->obj);
    }
}
