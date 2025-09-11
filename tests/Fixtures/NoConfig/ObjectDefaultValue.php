<?php

namespace Tests\Fixtures\NoConfig;


class ObjectDefaultValue
{
    public $obj;
    public function __construct(?ClassGraph $obj = null)
    {
        $this->obj = $obj;
    }
}
