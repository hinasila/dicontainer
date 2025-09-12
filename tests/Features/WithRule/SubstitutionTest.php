<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiContainerBuilder;
use Hinasila\DiContainer\Exception\ContainerException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Fixtures\Substitution\BasicClass;
use Tests\Fixtures\Substitution\BasicImplementation;
use Tests\Fixtures\Substitution\BasicInterface;
use Tests\Fixtures\Substitution\NullableSubtitution;

final class SubstitutionTest extends TestCase
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
        $this->expectExceptionMessage(\sprintf(
            'Argument 1 passed to %s::__construct() must implement interface %s, instance of %s given',
            BasicClass::class,
            BasicInterface::class,
            stdClass::class
        ));

        $dic = DiContainerBuilder::init()
            ->bind(BasicInterface::class, stdClass::class)
            ->createContainer();

        $dic->get(BasicClass::class);
    }

    public function test_substitution(): void
    {
        $dic = DiContainerBuilder::init()
            ->bind(BasicInterface::class, BasicImplementation::class)
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
