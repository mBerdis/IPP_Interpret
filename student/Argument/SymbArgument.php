<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Argument;
use IPP\Student\Argument\AbstractArgument;

class SymbArgument extends AbstractArgument
{
    public function __construct(string $value)
    {
        $split = explode("@", $value);
        parent::__construct($value);
    }
}