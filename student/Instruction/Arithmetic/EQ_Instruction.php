<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

class EQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $arg1 = $this->args[0];
        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== $type2) 
        {
            if ($type1 === "nil" || $type2 === "nil")
            {
                self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), false, "bool");
                return;
            }

            throw new OperandTypeException("EQ operand type mismatch! $type1 and $type2");
        }

        $data1 = self::get_arg_data($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $val = $data1 === $data2;
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}