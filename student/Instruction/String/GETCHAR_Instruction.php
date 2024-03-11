<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

 namespace IPP\Student\Instruction\String;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringOpException;

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