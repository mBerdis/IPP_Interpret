<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

class AND_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "bool" || $type2 !== "bool") 
            throw new OperandTypeException("AND: Operand type error! Expected bool, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) and self::get_arg_data($this->args[2]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}