<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Misc;
use IPP\Student\Instruction\AbstractInstruction;

class READ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "type");
        
        $arg = $this->args[0];

        $frame = $arg->get_frame();
        $name  = $arg->get_value();
        $type  = $this->args[1]->get_value();

        self::$interp->read_to_var($frame, $name, $type);
    } 
}