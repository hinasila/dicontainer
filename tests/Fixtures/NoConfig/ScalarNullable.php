<?php
namespace Tests\Fixtures\NoConfig;


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
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->array = $array;
    }
}
