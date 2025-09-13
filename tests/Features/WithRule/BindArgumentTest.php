<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Fixtures\BasicClass;
use Fixtures\BasicConcrete;
use Fixtures\BasicInterface;
use Fixtures\ConfigA;
use Fixtures\DefaultProvider;
use Fixtures\DefaultProviderInterface;
use Fixtures\MainWire;
use Fixtures\ProviderConfigInterface;
use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use Hinasila\DiContainer\Exception\ContainerException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class BindArgumentTest extends TestCase
{
    public function test_missing_substitution(): void
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(\sprintf(
            'Missing required substitutions %s passed to %s::__construct()',
            BasicInterface::class,
            BasicClass::class
        ));

        $dic = new DiContainer();

        $dic->get(BasicClass::class);
    }

    public function test_invalid_substitution(): void
    {
        $this->expectException(ContainerException::class);

        if (\PHP_VERSION_ID <= 70400) {
            $this->expectExceptionMessage(\sprintf(
                'Argument 1 passed to %s::__construct() must implement interface %s, instance of %s given',
                BasicClass::class,
                BasicInterface::class,
                stdClass::class
            ));
        }

        if (\PHP_VERSION_ID >= 80000) {
            $this->expectExceptionMessage(\sprintf(
                '%s::__construct(): Argument #1 ($obj) must be of type %s, %s given',
                BasicClass::class,
                BasicInterface::class,
                stdClass::class
            ));
        }

        $builder = new DiContainerBuilder();
        $builder->newRule(BasicClass::class)
            ->bindArg(BasicInterface::class, stdClass::class);

        $dic = $builder->createContainer();

        $dic->get(BasicClass::class);
    }

    public function test_binding(): void
    {
        $builder = new DiContainerBuilder();
        $builder->newRule(BasicClass::class)
            ->bindArg(BasicInterface::class, BasicConcrete::class);

        $dic = $builder->createContainer();

        $instance = $dic->get(BasicClass::class);

        $this->assertInstanceOf(BasicInterface::class, $instance->obj);
    }

    public function test_complex_wiring(): void
    {
        $builder = new DiContainerBuilder();
        $builder->newRule(DefaultProviderInterface::class, DefaultProvider::class)
            ->bindArg(ProviderConfigInterface::class, ConfigA::class);

        $dic = $builder->createContainer();

        $instance = $dic->get(MainWire::class);
        $this->assertInstanceOf(ConfigA::class, $instance->provider->config);
    }
}
