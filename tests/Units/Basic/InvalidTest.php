<?php declare(strict_types=1);

use Hinasila\DiContainer\ContainerException;
use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiRuleList;
use Hinasila\DiContainer\NotFoundException;
use PHPUnit\Framework\TestCase;
use Tests\Data\AbstractClass;
use Tests\Data\CyclicA;
use Tests\Data\PrivateClass;
use Tests\Data\ProtectedClass;
use Tests\Data\TraitTest;

final class InvalidTest extends TestCase
{
    /**
     * @var DiContainer
     */
    private $dic;

    protected function setUp(): void
    {
        $rlist     = new DiRuleList();
        $this->dic = new DiContainer($rlist);

        require_once DATA_DIR . '/Invalid.php';
    }

    public function testCreateInterface()
    {
        $msg = sprintf('Class or rule %s does not exist', 'ArrayAccess');
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(ArrayAccess::class);
    }

    public function testCreateTrait()
    {
        $msg = sprintf('Class or rule %s does not exist', TraitTest::class);
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage($msg);

        $this->dic->get(TraitTest::class);
    }

    public function testCreateAbstractClass()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cannot instantiate abstract class ' . AbstractClass::class);

        $this->dic->get(AbstractClass::class);
    }

    public function testCreatePrivateConstructor()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . PrivateClass::class);

        $this->dic->get(PrivateClass::class);
    }

    public function testProtectedConstructor()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Access to non-public constructor of class ' . ProtectedClass::class);

        $this->dic->get(ProtectedClass::class);
    }

    public function testCyclicDependencies()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Cyclic dependencies detected');

        $this->dic->get(CyclicA::class);
    }
}
