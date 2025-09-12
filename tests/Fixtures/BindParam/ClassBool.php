<?php
namespace Tests\Fixtures\BindParam;

class ClassBool extends ClassString {
    public function __construct(bool $required, ?bool $null, bool $optional = true)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}
