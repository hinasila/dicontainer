<?php declare(strict_types=1);

namespace Tests;

use Hinasila\DiContainer\DiContainer;
use PHPUnit\Framework\TestCase;

abstract class DicTestCase extends TestCase
{
    /**
     * @var DiContainer
     */
    protected $dic;

    protected function setUp(): void
    {
        $this->dic = new DiContainer();
    }
}
