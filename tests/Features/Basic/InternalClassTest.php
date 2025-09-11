<?php declare(strict_types=1);

namespace Tests\Features\Basic;

use DateTime;
use Tests\DicTestCase;

final class InternalClassTest extends DicTestCase
{
    /**
     * @group xdebug
     */
    public function test_without_constructor(): void
    {
        $obj = $this->dic->get(DateTime::class);
        $dt  = \date_create();

        $this->assertInstanceOf(DateTime::class, $obj);
        $this->assertSame($obj->format(\DATE_W3C), $dt->format(\DATE_W3C));
    }
}
