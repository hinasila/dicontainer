<?php declare(strict_types=1);


namespace Tests\Data;

use ArrayAccess;
use stdClass;

class ClassString{
    public $required;
    public $optional;
    public $null;
    public function __construct(string $required, ?string $null, string $optional = 'Optional')
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassBool{
    public function __construct(bool $required, ?bool $null, bool $optional = true)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassInt extends ClassString{
    public function __construct(int $required, ?int $null, int $optional = 2019)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassFloat extends ClassString{
    public function __construct(float $required, ?float $null, float $optional = 3.14)
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassArray extends ClassString{
    public function __construct(array $required, ?array $null, array $optional = [3.14])
    {
        $this->required = $required;
        $this->null = $null;
        $this->optional = $optional;
    }
}

class ClassMixed extends ClassString{
    public function __construct($required, $optional = 'Optional', $null = null)
    {
        $this->required = $required;
        $this->optional = $optional;
        $this->null = $null;
    }
}

class ObjectScalar{
    public $interface;
    public $class;
    public $bool;
    public $string;
    public $int;
    public $float;
    public $array;
    public $mixed;
    public function __construct(
        ?ArrayAccess $interface,
        ?stdClass $class,
        ?bool $bool,
        ?string $string,
        ?int $int,
        ?float $float,
        ?array $array,
        $mixed
    ) {
        $this->interface = $interface;
        $this->class = $class;
        $this->bool = $bool;
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->array = $array;
        $this->mixed = $mixed;
    }
}

class MixedArgument{
    public const PUBLIC_CONST = 1;
    private const PRIVATE_CONST = -1;

    public $arg;
    public function __construct($arg)
    {
        $this->arg = $arg;
    }
}
