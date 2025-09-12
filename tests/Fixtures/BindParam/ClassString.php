<?php

namespace Tests\Fixtures\BindParam;


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
