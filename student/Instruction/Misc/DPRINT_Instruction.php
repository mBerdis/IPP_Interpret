<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Misc;
use IPP\Student\Instruction\AbstractInstruction;

class DPRINT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $toWrite = self::get_arg_data($this->args[0]);
        $type = self::get_arg_type($this->args[0]);

        self::$interp->stderr_write($toWrite, $type);
    } 
}