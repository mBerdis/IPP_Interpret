<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

use IPP\Student\Exception\OperandValueException;
use IPP\Student\Exception\ValueException;

class VariableData
{
    private int|string|bool|null $value = null;
    private DataType $type;

    public function __construct()
    {
        $this->type = DataType::UNDEFINED;
    }

    public function set_var(int|string|bool $value, DataType $type): void
    {
        switch ($type) 
        {
            case DataType::BOOL:
                if (!is_bool($value))
                    throw new OperandValueException("Value $value is not of type bool");
                break;
            case DataType::INT:
                if (!is_int($value))
                    throw new OperandValueException("Value $value is not of type int");
                break;
            case DataType::STRING:
                if (!is_string($value))
                    throw new OperandValueException("Value $value is not of type string");
                break;
            default:
                break;
        }

        $this->value = $value;
        $this->type  = $type;
    }

    public function get_value(): int|string|bool
    {
        if ($this->type === DataType::UNDEFINED) 
            throw new ValueException();

        return $this->value;
    }

    public function get_type(): DataType
    {
        return $this->type;
    }

    public function get_type_str(): string
    {
        switch ($this->type) {
            case DataType::BOOL:
                return "bool";
            case DataType::INT:
                return "int";
            case DataType::STRING:
                return "string";   
            case DataType::NIL:
                return "nil";         
            default:
                return "";
        }
    }
}
