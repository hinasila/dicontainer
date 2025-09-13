<?php

namespace Fixtures;


class ClassString{
    public $required;
    public $optional;
    public $null;
    public function __construct(string $required, ?string $null, string $optional = 'Optional')
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}


class ClassBool extends ClassString {
    public function __construct(bool $required, ?bool $null, bool $optional = true)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassInt extends ClassString
{
    public function __construct(int $required, ?int $null, int $optional = 2019)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassFloat extends ClassString
{
    public function __construct(float $required, ?float $null, float $optional = 3.14)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassArray extends ClassString
{
    public function __construct(array $required, ?array $null, array $optional = [3.14])
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}
