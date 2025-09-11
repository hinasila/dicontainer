<?php

namespace Tests\Fixtures\NoConfig;

use stdClass;

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
    public $std;
    public function __construct(F $f, stdClass $std)
    {
        $this->f = $f;
        $this->std;
    }
}

class F{}
