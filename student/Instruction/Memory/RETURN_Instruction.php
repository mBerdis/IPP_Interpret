<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Memory;
use IPP\Student\Instruction\AbstractInstruction;

class RETURN_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $order = self::$interp->pop_call();
        self::$interp->set_current_order($order);
    } 
}