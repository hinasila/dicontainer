<?php declare(strict_types=1);

namespace Tests\Features\Basic;

use Fixtures\SelfSubstitute;
use Hinasila\DiContainer\DiContainer;
use ReflectionProperty;
use Tests\DicTestCase;

final class SelfSubstituteTest extends DicTestCase
{
    public function test_self_substitute(): void
    {
        $obj = $this->dic->get(SelfSubstitute::class);

        $this->assertInstanceOf(DiContainer::class, $obj->dic);

        $listVar = new ReflectionProperty(DiContainer::class, 'rules');
        $listVar->setAccessible(true);

        // Separate DiContainer with identical roles
        $this->assertNotSame($this->dic, $obj->dic);
        $this->assertSame($listVar->getValue($obj->dic), $listVar->getValue($this->dic));
    }
}
