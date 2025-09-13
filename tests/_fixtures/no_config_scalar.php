<?php declare(strict_types=1);

namespace Fixtures;


class ScalarDefaultValue
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


class ScalarNullable
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
        $this->int = $int;
        $this->float = $float;
        $this->array = $array;
    }
}
