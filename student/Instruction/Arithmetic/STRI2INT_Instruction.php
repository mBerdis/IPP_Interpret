<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Arithmetic;
use IPP\Student\Instruction\AbstractInstruction;

class STRI2INT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $str = self::get_arg_data($this->args[1]);
        $pos = self::get_arg_data($this->args[2]);

        $arg1 = $this->args[0];
        $val = ord($str[$pos]);

        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}