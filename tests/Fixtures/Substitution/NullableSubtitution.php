<?php

namespace Tests\Fixtures\Substitution;


class NullableSubtitution {
    public $obj;
    public function __construct(?BasicInterface $obj)
    {
        $this->obj = $obj;
    }
}
