<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Argument;

abstract class AbstractArgument
{
    private int|string|bool $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function get_value(): int|string|bool
    {
        return $this->value;
    }
}