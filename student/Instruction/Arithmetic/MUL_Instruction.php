<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

class MUL_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "int" || $type2 !== "int") 
            throw new OperandTypeException("MUL: Operand type error! Expected int, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = $data1 * $data2;
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}