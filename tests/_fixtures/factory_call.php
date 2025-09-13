<?php

namespace Fixtures;

interface ResultInterface{
    public function getValue(): string;
}
class ResultFactory implements ResultInterface{
    private $value;
    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }
}

class FactoryService {
    public $result;
    public function __construct(ResultInterface $result) {
        $this->result = $result;
    }
}
