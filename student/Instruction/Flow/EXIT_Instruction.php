<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Flow;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandValueException;

class EXIT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $val = self::get_arg_data($this->args[0]);
        
        if ($val < 0 || $val > 9) 
            throw new OperandValueException("Wrong operand value for EXIT!");

        self::$interp->set_exit_code($val);
        self::$interp->set_halt(true);
    } 
}