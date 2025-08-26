<?php declare(strict_types=1);


namespace Tests\Data;

use stdClass;

class Injector
{
    public function getClass(string $value) : stdClass
    {
        $std = new stdClass;
        $std->arg = 'Injector::getClass => ' . $value;
        return $std;
    }

    public function getArray() : array
    {
        return ['Injector', 'getArray'];
    }

    public function getMixedObject(string $value) : stdClass
    {
        $std = new stdClass;
        $std->value = $value;
        return $std;
    }
}


class ClassInjected
{
    public $obj;
    public function __construct(stdClass $obj){
        $this->obj = $obj;
    }
}

class ArrayInjected
{
    public $values;
    public function __construct(array $values){
        $this->values = $values;
    }
}
