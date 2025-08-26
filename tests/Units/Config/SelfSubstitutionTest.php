<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
use Hinasila\DiContainer\DiContainer;
use Tests\Units\Config\AbstractConfigTestCase;

final class SelfSubstitutionTest extends AbstractConfigTestCase
{
    public function testGetDiContainerInstance()
    {
        $obj = $this->dic->get(DicDependant::class);

        $this->assertInstanceOf(DiContainer::class, $obj->dic);

        $listVar = new ReflectionProperty(DiContainer::class, 'list');
        $listVar->setAccessible(true);

        // Different DiContainer instance but with the same role list
        $this->assertNotSame($this->dic, $obj->dic);
        $this->assertSame($listVar->getValue($obj->dic), $listVar->getValue($this->dic));
    }
}
