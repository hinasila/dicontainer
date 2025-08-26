<?php declare(strict_types=1);


namespace Tests\Data;


trait TraitTest {}

abstract class AbstractClass{}

class PrivateClass
{
    private function __construct(){}
}

class ProtectedClass
{
    protected function __construct(){}
}

class CyclicA {
    public function __construct(CyclicB $b){}
}
class CyclicB {
    public function __construct(CyclicA $a){}
}
