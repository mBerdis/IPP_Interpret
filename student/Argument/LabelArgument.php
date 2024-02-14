<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Argument;
use IPP\Student\Argument\AbstractArgument;

class LabelArgument extends AbstractArgument
{
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}