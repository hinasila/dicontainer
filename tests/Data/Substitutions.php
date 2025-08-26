<?php declare(strict_types=1);

/**
 * This file is part of xynha/dicontainer package.
 *
 * @author Asis Pattisahusiwa <asis.pattisahusiwa@gmail.com>
 * @copyright 2020 Asis Pattisahusiwa
 * @license https://github.com/pattisahusiwa/dicontainer/blob/master/LICENSE Apache-2.0 License
 */
namespace Tests\Data;


interface InterfaceNoRule{}
class ClassNoRule {
    public function __construct(InterfaceNoRule $obj){}
}


interface InterfaceA{}
class ImplementInterfaceA implements InterfaceA {
    public $arg;
    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }
}
class DependInterfaceA {
    public $obj;
    public function __construct(InterfaceA $obj)
    {
        $this->obj = $obj;
    }
}


interface InvalidSubsInterface{}
class ImplInvalidSubsInterface implements InvalidSubsInterface{}
class DependInvalidSubsInterface {
    public $obj;
    public function __construct(InvalidSubsInterface $obj)
    {
        $this->obj = $obj;
    }
}


class AllowsNullInterface {
    public $obj;
    public function __construct(?InterfaceA $obj)
    {
        $this->obj = $obj;
    }
}

class DefaultValueInterface {
    public $obj;
    public function __construct(InterfaceA $obj = null)
    {
        $this->obj = $obj;
    }
}
