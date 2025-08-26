<?php declare(strict_types=1);


class DicDependant
{

    public $dic;

    public function __construct(\Psr\Container\ContainerInterface $dic)
    {
        $this->dic = $dic;
    }
}
