<?php declare(strict_types=1);


namespace Tests\Data;


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

class ObjectAllowsNull
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
    public function __construct(ClassGraph $obj = null)
    {
        $this->obj = $obj;
    }
}

class ScalarTypeDefaultValue
{
    public $bool;
    public $string;
    public $int;
    public $float;
    public $boolArray;
    public $emptyArray;
    public $stringArray;
    public $intArray;
    public $floatArray;

    public function __construct(
        bool $bool = false,
        string $string = 'Default value',
        int $int = 6,
        float $float = 3.14,
        array $emptyArray = [],
        array $boolArray = [false, true],
        array $stringArray = ['default', 'value'],
        array $intArray = [6,11,7],
        array $floatArray = [3.14,3.8]
    ) {
        $this->bool = $bool;
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->emptyArray = $emptyArray;
        $this->boolArray = $boolArray;
        $this->stringArray = $stringArray;
        $this->intArray = $intArray;
        $this->floatArray = $floatArray;
    }
}

class ScalarAllowsNull
{
    public $bool;
    public $string;
    public $int;
    public $float;
    public $array;

    public function __construct(?bool $bool, ?string $string, ?int $int, ?float $float, ?array $array)
    {
        $this->bool = $bool;
        $this->string = $string;
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->array = $array;
    }
}
