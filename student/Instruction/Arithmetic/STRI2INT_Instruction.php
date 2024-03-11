<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;

use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringOpException;

class STRI2INT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $str = self::get_arg_data($this->args[1]);
        $pos = self::get_arg_data($this->args[2]);

        if (self::get_arg_type($this->args[1]) !== "string") 
            throw new OperandTypeException("STRI2INT: operand1 type not string!");

        if (self::get_arg_type($this->args[2]) !== "int") 
            throw new OperandTypeException("STRI2INT: operand2 type not int!");

        if ($pos >= strlen($str) || $pos < 0) 
            throw new StringOpException("STRI2INT: index out of range");

        $arg1 = $this->args[0];
        $val = ord($str[$pos]);

        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}