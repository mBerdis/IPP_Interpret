<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Exception;
use IPP\Core\ReturnCode;
use IPP\Core\Exception\IPPException;
use Throwable;

class VariableAccessException extends IPPException
{
    public function __construct(string $message = "Variable access error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::VARIABLE_ACCESS_ERROR, $previous, false);
    }
}