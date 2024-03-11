<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Exception;
use IPP\Core\ReturnCode;
use IPP\Core\Exception\IPPException;
use Throwable;

class StringOpException extends IPPException
{
    public function __construct(string $message = "String operation error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::STRING_OPERATION_ERROR, $previous, false);
    }
}