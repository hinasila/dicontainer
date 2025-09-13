<?php

namespace Fixtures;

class CyclicA {
    public function __construct(CyclicB $b){}
}
class CyclicB {
    public function __construct(CyclicA $a){}
}
