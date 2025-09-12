<?php
namespace Tests\Fixtures\Substitution;

final class BasicClass
{
    public $obj;

    public function __construct(BasicInterface $obj)
    {
        $this->obj = $obj;
    }
}
