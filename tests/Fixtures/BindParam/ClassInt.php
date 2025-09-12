<?php

namespace Tests\Fixtures\BindParam;

class ClassInt extends ClassString
{
    public function __construct(int $required, ?int $null, int $optional = 2019)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}
