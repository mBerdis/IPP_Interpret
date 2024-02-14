<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;
use IPP\Student\Exception\SemanticException;

class Argument
{
    private string  $value;
    private string  $type;
    private ?string $frame = null;

    public function __construct(string $value, string $type)
    {
        if ($type === "nil" && $value !== "nil") 
            throw new SemanticException("Wrong nil type!");

        $this->value = $value;
        $this->type  = $type;

        if ($this->is_var()) 
        {
            if (!strpos($value, "@")) 
                throw new SemanticException("Variable in wrong format!");

            $splitted = explode("@", $value, 2);
            $this->frame = $splitted[0];
            $this->value = $splitted[1];
        }
    }

    public function get_value(): string
    {
        return $this->value;
    }

    public function get_type(): string
    {
        return $this->type;
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