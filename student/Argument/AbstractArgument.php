<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Argument;

abstract class AbstractArgument
{
    private int|string|bool $value;

    final public function __construct(int|string|bool $value)
    {
        $this->value = $value;
        $this->validate();
    }

    abstract protected function validate(): void;

    public function get_value(): int
    {
        return $this->value;
    }
}