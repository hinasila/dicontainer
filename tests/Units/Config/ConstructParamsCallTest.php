<?php declare(strict_types=1);

use Hinasila\DiContainer\ContainerException;
use Tests\Data\ArrayInjected;
use Tests\Data\ClassInjected;
use Tests\Data\MixedArgument;
use Tests\Units\Config\AbstractConfigTestCase;

final class ConstructParamsCallTest extends AbstractConfigTestCase
{
    protected function setUp(): void
    {
        $this->files = ['ConstructParams.php'];
        parent::setUp();
    }

    // Required test
    public function testCallObjectTrue()
    {
        $std = new stdClass();

        $obj = $this->dic->get('$callobject_true');
        $this->assertInstanceOf(ClassInjected::class, $obj);
        $this->assertEquals($std, $obj->obj);
    }

    /**
     * Required test
     *
     * @coversNothing
     */
    public function testCallScalarTrue()
    {
        $obj = $this->dic->get('$callscalar_true');
        $this->assertInstanceOf(ArrayInjected::class, $obj);
        $this->assertSame([true], $obj->values);
    }

    /**
     * Required test
     *
     * @coversNothing
     */
    public function testCallScalarMixedTrue()
    {
        $obj = $this->dic->get('$callscalar_mixed_true');
        $this->assertInstanceOf(MixedArgument::class, $obj);
        $this->assertSame([true], $obj->arg);
    }

    public function testCallObject()
    {
        $obj = $this->dic->get(ClassInjected::class);

        $this->assertSame('Injector::getClass => Call object value', $obj->obj->arg);
    }

    public function testCallObjectInvalidScalar()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Require CALL::OBJECT, CALL::SCALAR given');

        $this->dic->get('$callobject_scalar');
    }

    public function testCallObjectInvalidConstant()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Require CALL::OBJECT, CALL::CONSTANT given');

        $this->dic->get('$callobject_constant');
    }

    public function testCallScalar()
    {
        $obj = $this->dic->get(ArrayInjected::class);

        $this->assertSame(['Injector', 'getArray'], $obj->values);
    }

    public function testCallScalarInvalidObject()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Require CALL::SCALAR or CALL::CONSTANT, CALL::OBJECT given');

        $this->dic->get('$callscalar_object');
    }

    public function testCallConstantScalar()
    {
        $obj = $this->dic->get('$callconstant_scalar');

        $this->assertSame(DATA_DIR, $obj->required);
    }

    public function testCallConstantScalarEmpty()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid CALL::CONSTANT format');

        $this->dic->get('$callconstant_scalar_empty');
    }

    public function testCallConstantScalarNonString()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid CALL::CONSTANT format');

        $this->dic->get('$callconstant_scalar_non_string');
    }

    public function testMixedCallObject()
    {
        $obj = $this->dic->get('$mixed_callobject');

        $this->assertSame('Mixed object', $obj->arg->value);
    }

    public function testMixedCallScalar()
    {
        $obj = $this->dic->get('$mixed_callscalar');

        $this->assertSame(['Injector', 'getArray'], $obj->arg);
    }

    public function testMixedCallConstant()
    {
        $obj = $this->dic->get('$mixed_callconstant');

        $this->assertSame(DATA_DIR, $obj->arg);
    }

    public function testMixedCallConstantScalarEmpty()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid CALL::CONSTANT format');

        $this->dic->get('$mixed_callconstant_empty');
    }

    public function testMixedCallConstantScalarNonString()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Invalid CALL::CONSTANT format');

        $this->dic->get('$mixed_callconstant_non_string');
    }
}
