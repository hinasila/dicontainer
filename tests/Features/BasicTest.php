<?php declare(strict_types=1);

namespace Tests\Features;

use DateTime;
use Tests\DicTestCase;

final class BasicTest extends DicTestCase
{
    public function testInternalClass()
    {
        $obj = $this->dic->get(DateTime::class);
        $dt  = \date_create();

        $this->assertInstanceOf(DateTime::class, $obj);
        $this->assertSame($obj->format(\DATE_W3C), $dt->format(\DATE_W3C));
    }
}
