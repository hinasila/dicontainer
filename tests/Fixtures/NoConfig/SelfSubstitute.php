<?php declare(strict_types=1);

namespace Tests\Fixtures\NoConfig;

class SelfSubstitute
{

    public $dic;

    public function __construct(\Psr\Container\ContainerInterface $dic)
    {
        $this->dic = $dic;
    }
}
