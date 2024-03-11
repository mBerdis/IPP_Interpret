<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\String;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

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