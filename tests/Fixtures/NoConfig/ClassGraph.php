<?php

namespace Tests\Fixtures\NoConfig;


class ClassGraph
{
    public $b;
    public function __construct(B $b)
    {
        $this->b = $b;
    }
}


class B
{
    public $c;
    public function __construct(C $c)
    {
        $this->c = $c;
    }
}

class C
{
    public $d;
    public $e;
    public function __construct(D $d, E $e)
    {
        $this->d = $d;
        $this->e = $e;
    }
}

class D{}

class E
{
    public $f;
    public function __construct(F $f)
    {
        $this->f = $f;
    }
}

class F{}
