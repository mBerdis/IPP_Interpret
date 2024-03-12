<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringOpException;

class INT2CHAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        if (self::get_arg_type($this->args[1]) !== "int") 
            throw new OperandTypeException("STRI2INT: operand1 type not int!");

        $arg1 = $this->args[0];
        $val = mb_chr(self::get_arg_data($this->args[1]));

        if ($val == false)
            throw new StringOpException("STRI2INT: wrong ord value.");

        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "string");
    } 
}