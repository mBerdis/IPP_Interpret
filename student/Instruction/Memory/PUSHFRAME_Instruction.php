<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Memory;
use IPP\Student\Instruction\AbstractInstruction;

class PUSHFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->push_frame();
    } 
}