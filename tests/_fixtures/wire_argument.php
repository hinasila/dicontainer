<?php

namespace Fixtures;

interface BasicInterface{}
class BasicConcrete implements BasicInterface{}

final class BasicClass
{
    public $obj;

    public function __construct(BasicInterface $obj)
    {
        $this->obj = $obj;
    }
}


class NullableSubtitution {
    public $obj;
    public function __construct(?BasicInterface $obj)
    {
        $this->obj = $obj;
    }
}


class MainWire {
    /**
     * @var DefaultProvider
     */
    public $provider;
    public function __construct(DefaultProviderInterface $provider) {
        $this->provider = $provider;
    }
}

class DefaultProvider implements DefaultProviderInterface {
    public $config;
    public function __construct(ProviderConfigInterface $config) {
        $this->config = $config;
    }
}

class ConfigA implements ProviderConfigInterface {}
class ConfigB implements ProviderConfigInterface {}

interface DefaultProviderInterface{}
interface ProviderConfigInterface{}
