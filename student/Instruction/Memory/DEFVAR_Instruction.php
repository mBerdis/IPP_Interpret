<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Memory;
use IPP\Student\Instruction\AbstractInstruction;

class DEFVAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        $frame = $this->args[0]->get_frame();
        $name  = $this->args[0]->get_value();
        self::$interp->add_variable($frame, $name);
    } 
}