<?php declare(strict_types=1);

use Hinasila\DiContainer\ContainerException;
use Hinasila\DiContainer\DiContainer;
use Tests\Data\ClassMixed;
use Tests\Data\ClassString;
use Tests\Units\Config\AbstractConfigTestCase;

final class ConstructParamsTest extends AbstractConfigTestCase
{
    protected function setUp(): void
    {
        $this->files = ['BasicClass.php'];
        parent::setUp();
    }

    public function testNoConstructParams()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'required',
                ClassString::class
            )
        );

        $this->dic->get('$no_constructParams');
    }

    public function testInvalidType()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'required',
                ClassString::class
            )
        );

        $this->dic->get('$invalidType');
    }

    public function testBool()
    {
        $obj = $this->dic->get('$bool');

        $this->assertFalse($obj->required);
        $this->assertTrue($obj->optional);
        $this->assertNull($obj->null);
    }

    public function testString()
    {
        $obj = $this->dic->get('$string');

        $this->assertSame('Test string', $obj->required);
        $this->assertSame('Optional', $obj->optional);
        $this->assertNull($obj->null);
    }

    public function testInt()
    {
        $obj = $this->dic->get('$int');

        $this->assertSame(2020, $obj->required);
        $this->assertSame(2019, $obj->optional);
        $this->assertNull($obj->null);
    }

    public function testFloat()
    {
        $obj = $this->dic->get('$float');

        $this->assertSame(1e9, $obj->required);
        $this->assertSame(3.14, $obj->optional);
        $this->assertNull($obj->null);
    }

    public function testArray()
    {
        $obj = $this->dic->get('$array');

        $this->assertSame([], $obj->required);
        $this->assertSame([3.14], $obj->optional);
        $this->assertNull($obj->null);
    }

    public function testScalarType()
    {
        $dt                      = new DateTime();
        $rule['constructParams'] = [
          null, // $interface
          null, // $class
          null, // $bool
          null, // $string
          null, // $int
          null, // $float
          null, // $array
          $dt, // $mixed
        ];
        $rlist                   = $this->rlist->addRule('$scalar_type_null', $rule);
        $dic                     = new DiContainer($rlist);

        $obj = $dic->get('$scalar_type_null');

        $this->assertSame($dt, $obj->mixed);
    }

    public function testMixedMissingValue()
    {
        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Missing required argument $%s passed to %s::__construct()',
                'required',
                ClassMixed::class
            )
        );

        $this->dic->get('$mixed_missing_value');
    }

    public function testMixed()
    {
        $obj = $this->dic->get('$mixed');

        $this->assertNull($obj->required);
        $this->assertSame('Optional', $obj->optional);
        $this->assertNull($obj->null);
    }
}
