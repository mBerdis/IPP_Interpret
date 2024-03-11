<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;

class INT2CHAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $arg1 = $this->args[0];
        $val = chr(self::get_arg_data($this->args[1]));
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "string");
    } 
}