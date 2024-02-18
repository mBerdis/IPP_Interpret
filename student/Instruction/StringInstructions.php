<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Exception\StringOpException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Instruction\AbstractInstruction;

class CONCAT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);

        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "string" && $type2 !== "string") 
            throw new OperandTypeException("CONCAT: Operand type error! Expected string, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = $data1 . $data2;
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "string");
    } 
}

class STRLEN_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);

        if ($type1 !== "string") 
            throw new OperandTypeException("STRLEN: Operand type error! Expected string, got $type1");

        $arg1 = $this->args[0];
        $val = strlen($data1);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class GETCHAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $str   = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);

        $index = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "string" || $type2 !== "int") 
            throw new OperandTypeException("GETCHAR: Operand type error! Expected string and int, got $type1 and $type2");
        
        if (!isset($str[$index]))
            throw new StringOpException("GETCHAR: index out of bounds!");

        $arg1 = $this->args[0];
        $val  = $str[$index];
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "string");
    } 
}

class SETCHAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $index = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);

        $replStr = self::get_arg_data($this->args[2]);
        $type2   = self::get_arg_type($this->args[2]);

        if ($type1 !== "int" || $type2 !== "string") 
            throw new OperandTypeException("SETCHAR: Operand type error! Expected int and string, got $type1 and $type2");

        $arg1  = $this->args[0];
        $str   = self::get_arg_data($arg1);

        if (!isset($str[$index]) || !isset($replStr[0]))
            throw new StringOpException("SETCHAR: index out of bounds!");

        $str[$index] = $replStr[0];
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $str, "string");
    }
}