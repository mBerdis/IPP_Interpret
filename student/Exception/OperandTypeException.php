<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Exception;
use IPP\Core\ReturnCode;
use IPP\Core\Exception\IPPException;
use Throwable;

class OperandTypeException extends IPPException
{
    public function __construct(string $message = "Operand type error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::OPERAND_TYPE_ERROR, $previous, false);
    }
}