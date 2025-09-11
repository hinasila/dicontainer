<?php

namespace Tests\Fixtures\NoConfig;


class NullableObject
{
    public $std;
    public function __construct(?ClassGraph $std)
    {
        $this->std = $std;
    }
}
