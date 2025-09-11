<?php declare(strict_types=1);

namespace Tests;

use Hinasila\DiContainer\DiContainerBuilder;
use PHPUnit\Framework\TestCase;

abstract class DicTestCase extends TestCase
{
    /**
     * @var DiContainer
     */
    protected $dic;

    protected function setUp(): void
    {
        $this->dic = DiContainerBuilder::init()
            ->createContainer();

        // $rlist     = new DiRuleList();
        // $this->dic = new DiContainer($rlist);

        // require_once \DATA_DIR . '/BasicClass.php';
    }
}
