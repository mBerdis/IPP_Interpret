<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\SemanticException;

class Argument
{
    private int|string|bool  $value;
    private string  $type;
    private ?string $frame = null;

    public function __construct(int|string|bool $value, string $type)
    {
        if ($type === "nil" && $value !== "nil") 
            throw new SemanticException("Wrong nil type!");

        $this->value = $value;
        $this->type  = $type;

        if ($type === "var") 
        {
            if (!strpos($value, "@")) 
                throw new SemanticException("Variable in wrong format!");

            $splitted = explode("@", $value, 2);
            $this->frame = $splitted[0];
            $this->value = $splitted[1];
        }
        if ($type === "string") 
        {
            // replace escaped seq with corresponding chars
            $this->value = preg_replace_callback('/\\\\(\d{3})/', function ($matches) {
                return chr(intval($matches[1]));
            }, $this->value);
        }
    }

    public function get_value(): int|string|bool
    {
        return $this->value;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_frame(): string
    {
        if (!isset($this->frame)) 
            throw new OperandTypeException();
        
        return $this->frame;
    }

    public function is_var(): bool 
    {
        return $this->type === "var";
    }

    public function is_nil(): bool 
    {
        return $this->type === "nil";
    }
}