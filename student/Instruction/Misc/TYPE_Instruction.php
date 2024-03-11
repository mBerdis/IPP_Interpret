<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Misc;
use IPP\Student\Instruction\AbstractInstruction;

class TYPE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type = self::get_arg_type($this->args[1]);
        $arg = $this->args[0];

        self::$interp->update_variable($arg->get_frame(), $arg->get_value(), $type, "string");
    } 
}