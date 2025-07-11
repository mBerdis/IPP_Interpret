<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Exception;
use IPP\Core\ReturnCode;
use IPP\Core\Exception\IPPException;
use Throwable;

class ValueException extends IPPException
{
    public function __construct(string $message = "Value error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::VALUE_ERROR, $previous, false);
    }
}