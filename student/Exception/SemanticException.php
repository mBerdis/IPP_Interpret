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