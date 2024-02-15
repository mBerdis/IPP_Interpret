<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Exception;

use IPP\Core\ReturnCode;
use IPP\Core\Exception\IPPException;
use Throwable;

class SemanticException extends IPPException
{
    public function __construct(string $message = "Semantic error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::SEMANTIC_ERROR, $previous, false);
    }
}

class OperandTypeException extends IPPException
{
    public function __construct(string $message = "Operand type error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::OPERAND_TYPE_ERROR, $previous, false);
    }
}

class VariableAccessException extends IPPException
{
    public function __construct(string $message = "Variable access error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::VARIABLE_ACCESS_ERROR, $previous, false);
    }
}

class FrameAccessException extends IPPException
{
    public function __construct(string $message = "Frame access error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::FRAME_ACCESS_ERROR, $previous, false);
    }
}

class ValueException extends IPPException
{
    public function __construct(string $message = "Value error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::VALUE_ERROR, $previous, false);
    }
}

class OperandValueException extends IPPException
{
    public function __construct(string $message = "Operand value error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::OPERAND_VALUE_ERROR, $previous, false);
    }
}

class StringOpException extends IPPException
{
    public function __construct(string $message = "String operation error!", ?Throwable $previous = null)
    {
        parent::__construct($message, ReturnCode::STRING_OPERATION_ERROR, $previous, false);
    }
}
