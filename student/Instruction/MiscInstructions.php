<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

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
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
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
            // get value
            $toWrite = "its variable";
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