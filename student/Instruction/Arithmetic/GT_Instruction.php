<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

class GT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        if ($this->args[1]->is_nil() || $this->args[2]->is_nil()) 
            throw new OperandTypeException("GT operand is type nil!");

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== $type2) 
            throw new OperandTypeException("GT operand type mismatch $type1 and $type2!");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) > self::get_arg_data($this->args[2]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}