<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\DataStack;
use IPP\Student\Instruction\AbstractInstruction;

class PUSHS_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $data1 = self::get_arg_data($this->args[0]);
        $type1 = self::get_arg_type($this->args[0]);
        
        self::$interp->push_data($data1, $type1);
    } 
}