<?php declare(strict_types=1);


namespace Tests\Data;


interface FactoryInterface{}
class FactoryInterfaceImpl implements FactoryInterface
{
    public $generated;
    public $passed = 'Initial Value';
    public function __construct(string $generated)
    {
        $this->generated = $generated;
    }
    public function setValue(string $passed)
    {
        $this->passed = $passed;
    }
}
class FactoryInterfaceDep
{
    public $obj;
    public function __construct(FactoryInterface $arg)
    {
        $this->obj = $arg;
    }
}
class FactoryInterfaceFactory
{
    private $obj;
    public function __construct()
    {
        $this->obj = new FactoryInterfaceImpl(uniqid('factory_'));
    }

    public function getInstance(string $passed)
    {
        $this->obj->setValue($passed);
        return $this->obj;
    }
}



class ClassMap
{
    public $arg;
    public function __construct(string $arg)
    {
        $this->arg = $arg;
    }
}


class ClassMapper
{
    public $map;
    public function __construct(ClassMap $map)
    {
        $this->map = $map;
    }
}

class ClassGenerator
{
    public $map;
    public function __construct(ClassMap $map)
    {
        $this->map = $map;
    }
}

class MapFactory
{
    public $map;
    public $mapper;
    public $generator;
    public function __construct()
    {
        $this->map = new ClassMap(uniqid('MapFactory_'));
    }

    public function getMapper() : ClassMapper
    {
        if (!$this->mapper) {
            $this->mapper = new ClassMapper($this->map);
        }
        return $this->mapper;
    }

    public function getGenerator() : ClassGenerator
    {
        if (!$this->generator) {
            $this->generator = new ClassGenerator($this->map);
        }
        return $this->generator;
    }
}

class MapperDep
{
    public $mapper;
    public function __construct(ClassMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}

class GeneratorDep
{
    public $gen;
    public function __construct(ClassGenerator $gen)
    {
        $this->gen = $gen;
    }
}
