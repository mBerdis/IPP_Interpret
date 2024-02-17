<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\DataType;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Instruction\AbstractInstruction;

class PUSHS_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class POPS_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class READ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "type");
        
        $arg = $this->args[0];

        $frame = $arg->get_frame();
        $name  = $arg->get_value();
        $type  = $this->args[1]->get_value();

        self::$interp->read_to_var($frame, $name, $type);
    } 
}

class WRITE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $arg = $this->args[0];

        $toWrite = $arg->is_nil()? "" : $arg->get_value();

        if ($arg->is_var())
        {
            $frame = $arg->get_frame();
            $name  = $arg->get_value();
            $toWrite = self::$interp->get_variable_data($frame, $name);
        }

        echo($toWrite);
    } 
}

class TYPE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class DPRINT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class BREAK_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}