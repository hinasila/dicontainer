<?php

namespace Tests\Fixtures\BindParam;

class ClassFloat extends ClassString
{
    public function __construct(float $required, ?float $null, float $optional = 3.14)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}
