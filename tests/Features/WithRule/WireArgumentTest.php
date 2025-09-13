<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use Hinasila\DiContainer\Exception\ContainerException;
use Hinasila\DiContainer\Rule\RuleBuilder;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\Substitution\BasicClass;
use Tests\Fixtures\Substitution\BasicImplementation;
use Tests\Fixtures\Substitution\BasicInterface;
use Tests\Fixtures\Substitution\NullableSubtitution;

final class WireArgumentTest extends TestCase
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


        $dic = (new DiContainerBuilder())
            ->addRule(BasicClass::class, static function (RuleBuilder $rule): void {
                $rule->wireArg(BasicInterface::class, stdClass::class);
            })
            ->createContainer();

        $dic->get(BasicClass::class);
    }

    public function test_wiring(): void
    {
        $dic = (new DiContainerBuilder())
            ->addRule(BasicClass::class, static function (RuleBuilder $rule): void {
                $rule->wireArg(BasicInterface::class, BasicImplementation::class);
            })
            ->createContainer();

        $instance = $dic->get(BasicClass::class);

        $this->assertInstanceOf(BasicInterface::class, $instance->obj);
    }

    public function test_nullable(): void
    {
        $dic = new DiContainer();

        $instance = $dic->get(NullableSubtitution::class);

        $this->assertNull($instance->obj);
    }
}
