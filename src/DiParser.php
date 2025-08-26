<?php declare(strict_types=1);

namespace Hinasila\DiContainer;

use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

final class DiParser
{
    /**
     * @var callable
     */
    private $creator;

    /**
     * @var CallbackHelper
     */
    private $helper;

    public function __construct(callable $creator, CallbackHelper $helper)
    {
        $this->creator = $creator;
        $this->helper  = $helper;
    }

    /**
     * @param array<mixed> $passedValues
     * @param array<string,string> $subs
     *
     * @return array<mixed>
     */
    public function parse(?ReflectionMethod $method, array $passedValues, array $subs): array
    {
        if ($method === null) {
            return [];
        }

        if ($method->isPrivate() === true || $method->isProtected() === true) {
            $name = $method->getDeclaringClass()->getName();
            $msg  = 'Access to non-public constructor of class ' . $name;
            throw new ContainerException($msg);
        }

        $tParams = [];
        $params  = $method->getParameters();
        foreach ($params as $arg) {
            $varName = $arg->getName();
            $type    = $arg->getType();

            if ($type instanceof ReflectionNamedType) {
                $tParams[$varName] = $this->processNameType($arg, $type, $subs, $passedValues);
                continue;
            }

            $tParams[$varName] = $this->mixedValue($arg, $passedValues);
        }

        return $tParams;
    }

    /**
     * @param array<string,string> $subs
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function processNameType(ReflectionParameter $param, ReflectionNamedType $type, array $subs, array &$values)
    {
        if (\interface_exists($type->getName()) === true) {
            return $this->interfaceValue($param, $type, $subs, $values);
        }

        if (\class_exists($type->getName()) === true) {
            return $this->classValue($param, $type, $values);
        }

        return $this->scalarValue($param, $type, $values);
    }

    /**
     * @param array<string,string> $subs
     * @param array<mixed> $values
     *
     * @return void|null|object
     */
    private function interfaceValue(ReflectionParameter $param, ReflectionNamedType $type, array $subs, array &$values)
    {
        try {
            return $this->getObjectValue($type->getName(), $values, $param->allowsNull());
        } catch (NoValueException $exc) {
        }

        if (\array_key_exists($type->getName(), $subs) === true) {
            return \call_user_func_array($this->creator, [$subs[$type->getName()]]);
        }

        if ($param->isDefaultValueAvailable() === true) {
            return $param->getDefaultValue();
        }

        if ($param->allowsNull() === true) {
            return null;
        }

        try {
            return \call_user_func_array($this->creator, [$type->getName()]);
        } catch (NotFoundException $exc) {
            $msg = \sprintf(
                'Missing required substitutions %s passed to %s::%s()',
                $type->getName(),
                $param->getDeclaringClass()->getName(),
                $param->getDeclaringFunction()->getName()
            );
            throw new ContainerException($msg, $exc->getCode(), $exc);
        }
    }

    /**
     * @param array<mixed> $values
     *
     * @return void|null|object
     */
    private function classValue(ReflectionParameter $param, ReflectionNamedType $type, array &$values)
    {
        try {
            return $this->getObjectValue($type->getName(), $values, $param->allowsNull());
        } catch (NoValueException $exc) {
        }

        if ($param->isDefaultValueAvailable() === true) {
            return $param->getDefaultValue();
        }

        if ($param->allowsNull() === true) {
            return null;
        }

        return \call_user_func_array($this->creator, [$type->getName()]);
    }

    /**
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function scalarValue(ReflectionParameter $param, ReflectionNamedType $type, array &$values)
    {
        try {
            return $this->scalarFromValue($type, $values, $param->allowsNull());
        } catch (NoValueException $exc) {
        }

        if ($param->isDefaultValueAvailable() === true) {
            return $param->getDefaultValue();
        }

        if ($param->allowsNull() === true) {
            return null;
        }

        $msg = \sprintf(
            'Missing required argument $%s passed to %s::%s()',
            $param->getName(),
            $param->getDeclaringClass()->getName(),
            $param->getDeclaringFunction()->getName()
        );
        throw new ContainerException($msg);
    }

    /**
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function scalarFromValue(ReflectionNamedType $type, array &$values, bool $allowsNull)
    {
        if (\count($values) <= 0) {
            throw new NoValueException();
        }

        if (\is_array($values[0]) === true) {
            $call = (string) ($values[0][0] ?? '');
            switch ($call) {
                case 'CALL::OBJECT':
                    throw new ContainerException('Require CALL::SCALAR or CALL::CONSTANT, CALL::OBJECT given');
                case 'CALL::SCALAR':
                    return $this->doCall(\array_shift($values));
                case 'CALL::CONSTANT':
                    $const = \array_shift($values);
                    if (empty($const[1]) === true || \is_string($const[1]) === false) {
                        throw new ContainerException('Invalid CALL::CONSTANT format');
                    }
                    return \constant($const[1]);
            }
        }

        if ($this->sameType($type->getName(), $values[0]) === true) {
            return \array_shift($values);
        }

        if ($values[0] === null && $allowsNull === true) {
            return \array_shift($values);
        }

        throw new NoValueException();
    }

    /** @param mixed $value */
    private function sameType(string $type, $value): bool
    {
        switch ($type) {
            case 'bool':
                return \is_bool($value);
            case 'string':
                return \is_string($value);
            case 'int':
                return \is_int($value);
            case 'float':
                return \is_float($value);
        }

        return \is_array($value);
    }

    /**
     * @param array<mixed> $values
     *
     * @return object|null
     */
    private function getObjectValue(string $className, array &$values, bool $allowsNull)
    {
        if (\count($values) <= 0) {
            throw new NoValueException();
        }

        if (\is_object($values[0]) === true && ($values[0] instanceof $className)) {
            return \array_shift($values);
        }

        if ($values[0] === null && $allowsNull === true) {
            return \array_shift($values);
        }

        $call = (string) ($values[0][0] ?? '');
        switch ($call) {
            case 'CALL::OBJECT':
                return $this->doCall(\array_shift($values));
            case 'CALL::SCALAR':
                throw new ContainerException('Require CALL::OBJECT, CALL::SCALAR given');
            case 'CALL::CONSTANT':
                throw new ContainerException('Require CALL::OBJECT, CALL::CONSTANT given');
            default:
                throw new NoValueException();
        }
    }

    /**
     * @param array{string,string,array<mixed>} $values
     *
     * @return  mixed
     */
    private function doCall(array $values)
    {
        \array_shift($values);
        $callback = \array_shift($values);
        $args     = \array_shift($values);

        $callback = $this->helper->toCallback($callback);
        return \call_user_func_array($callback, (array) $args);
    }

    /**
     * @param array<mixed> $values
     *
     * @return mixed
     */
    private function mixedValue(ReflectionParameter $param, array &$values)
    {
        if (isset($values[0]) === true && \is_array($values[0]) === true) {
            $call = (string) ($values[0][0] ?? '');
            switch ($call) {
                case 'CALL::OBJECT':
                    return $this->doCall(\array_shift($values));
                case 'CALL::SCALAR':
                    return $this->doCall(\array_shift($values));
                case 'CALL::CONSTANT':
                    $const = \array_shift($values);
                    if (empty($const[1]) === true || \is_string($const[1]) === false) {
                        throw new ContainerException('Invalid CALL::CONSTANT format');
                    }
                    return \constant($const[1]);
            }
        }

        if (\count($values) > 0) {
            return \array_shift($values);
        }

        if ($param->isOptional() === false) {
            $msg = \sprintf(
                'Missing required argument $%s passed to %s::%s()',
                $param->getName(),
                $param->getDeclaringClass()->getName(),
                $param->getDeclaringFunction()->getName()
            );
            throw new ContainerException($msg);
        }

        if ($param->isDefaultValueAvailable() === true) {
            return $param->getDefaultValue();
        }
    }
}
