<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Misc;
use IPP\Student\Instruction\AbstractInstruction;

class BREAK_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->stderr_write("DEBUG INFO: ", "string");
        self::$interp->stderr_write(" current instruction order: ", "string");
        
        $order = self::$interp->get_current_order();
        self::$interp->stderr_write($order, "int");
    } 
}