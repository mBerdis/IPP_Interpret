<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Argument;

abstract class AbstractArgument
{
    private $value;

    final public function __construct($value)
    {
        $this->value = $value;
        $this->validate();
    }

    protected function validate()
    {
        echo "here";
    }

    public function get_value()
    {
        return $this->value;
    }
}