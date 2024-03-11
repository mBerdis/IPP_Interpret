<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

class NOT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type = self::get_arg_type($this->args[1]);
        if ($type !== "bool") 
            throw new OperandTypeException("OR: Operand type error! Expected bool, got $type");
        
        $arg1 = $this->args[0];
        $val = !self::get_arg_data($this->args[1]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}