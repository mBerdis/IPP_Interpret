<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\DataStack;
use IPP\Student\Instruction\AbstractInstruction;

class POPS_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $storage = self::$interp->pop_data();
        $data    = $storage->get_value();
        $type    = $storage->get_type_str();
        
        $arg1   = $this->args[0];
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $data, $type);
    } 
}