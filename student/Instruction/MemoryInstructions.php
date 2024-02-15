<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Exception\SemanticException;
use IPP\Student\Instruction\AbstractInstruction;

class MOVE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class CREATEFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class PUSHFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class POPFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class DEFVAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        $frame = $this->args[0]->get_frame();
        $name  = $this->args[0]->get_value();
        self::$interp->add_variable($frame, $name);
    } 
}

class CALL_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class RETURN_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}