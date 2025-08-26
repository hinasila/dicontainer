<?php declare(strict_types=1);


function fnCallback(string $string, array $array) : array
{
    return [$string, $array];
}


class StaticClass
{
    public static function callback(string $string, array $array) : array
    {
        return ['StaticClass', $string, $array];
    }
}

class ExtendStaticClass extends StaticClass
{
    public static function callback(string $string, array $array) : array
    {
        return ['ExtendStaticClass', $string, $array];
    }
}

class InvokeClass
{
    public function callback(string $string, array $array) : array
    {
        return ['InvokeClass', $string, $array];
    }

    public function __invoke(string $string, array $array) : array
    {
        return ['InvokeClass::__invoke', $string, $array];
    }
}
