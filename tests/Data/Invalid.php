<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
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
