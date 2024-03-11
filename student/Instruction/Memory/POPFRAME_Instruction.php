<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Memory;
use IPP\Student\Instruction\AbstractInstruction;

class POPFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->pop_frame();
    } 
}