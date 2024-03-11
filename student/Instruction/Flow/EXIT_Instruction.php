<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Flow;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandValueException;
use IPP\Student\Exception\OperandTypeException;

class EXIT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $type = self::get_arg_type($this->args[0]);
        $val  = self::get_arg_data($this->args[0]);

        if ($type !== "int") 
            throw new OperandTypeException("EXIT: Operand type error! Expected int, got $type");
        
        if ($val < 0 || $val > 9) 
            throw new OperandValueException("Wrong operand value for EXIT!");

        self::$interp->set_exit_code($val);
        self::$interp->set_halt(true);
    } 
}