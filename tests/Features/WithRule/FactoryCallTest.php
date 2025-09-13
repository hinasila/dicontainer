<?php declare(strict_types=1);

namespace Tests\Features\WithRule;

use Fixtures\FactoryService;
use Fixtures\ResultFactory;
use Fixtures\ResultInterface;
use Hinasila\DiContainer\DiContainerBuilder;
use PHPUnit\Framework\TestCase;

final class FactoryCallTest extends TestCase
{
    public function test_factory_call(): void
    {
        $builder = new DiContainerBuilder();
        $builder->newRule(ResultInterface::class)
            ->useClosure(static function (): ResultFactory {
                return ResultFactory::create('From closure');
            });

        $dic = $builder->createContainer();

        $instance = $dic->get(FactoryService::class);

        $this->assertSame('From closure', $instance->result->getValue());
    }
}
