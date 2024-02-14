<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Exception\SemanticException;
use IPP\Student\Instruction\AbstractInstruction;

class LABEL_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        if (!isset($this->args[0])) 
            throw new SemanticException("Wrong label argument!");
        
        $label = $this->args[0]->get_value();
        self::$interp->add_label($label, $this->order);
    } 
}

class JUMP_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class JUMPIFEQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class JUMPIFNEQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}

class EXIT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        //echo($this->args[0]->get_value());
        //echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}