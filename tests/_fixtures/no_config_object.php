<?php declare(strict_types=1);

namespace Fixtures;

use stdClass;

class SelfSubstitute
{

    public $dic;

    public function __construct(\Psr\Container\ContainerInterface $dic)
    {
        $this->dic = $dic;
    }
}

class ClassGraph { public $b; public function __construct(B $b) {$this->b = $b;}}
class B { public $c; public function __construct(C $c) { $this->c = $c;} }
class C { public $d; public $e; public function __construct(D $d, E $e) { $this->d = $d; $this->e = $e; } }
class D{}
class E { public $f; public $std; public function __construct(F $f, stdClass $std) { $this->f = $f; $this->std = $std; } }
class F{}




class NullableObject
{
    public $std;
    public function __construct(?ClassGraph $std)
    {
        $this->std = $std;
    }
}


class ObjectDefaultValue
{
    public $obj;
    public function __construct(?ClassGraph $obj = null)
    {
        $this->obj = $obj;
    }
}
