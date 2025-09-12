<?php

namespace Tests\Fixtures\BindParam;

class ClassArray extends ClassString
{
    public function __construct(array $required, ?array $null, array $optional = [3.14])
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}
