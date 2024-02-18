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

        if ($arg->is_var())
        {
            $frame = $arg->get_frame();
            $name  = $arg->get_value();
            $toWrite = self::$interp->get_variable_data($frame, $name);
        }
        else 
        {
            $toWrite = $arg->get_value();
        }

        self::$interp->stdout_write($toWrite, $arg->get_type());
    } 
}

class TYPE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        if ($this->args[1]->is_var())
            $type = self::$interp->get_variable_type($this->args[1]->get_frame(), $this->args[1]->get_value());
        else
            $type = $this->args[1]->get_type();

        $arg = $this->args[0];
        $frame = $arg->get_frame();
        $name  = $arg->get_value();

        self::$interp->update_variable($frame, $name, $type, "string");
    } 
}

class DPRINT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $arg = $this->args[0];

        if ($arg->is_var())
        {
            $frame = $arg->get_frame();
            $name  = $arg->get_value();
            $toWrite = self::$interp->get_variable_data($frame, $name);
        }
        else 
        {
            $toWrite = $arg->get_value();
        }

        self::$interp->stderr_write($toWrite, $arg->get_type());
    } 
}

class BREAK_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->stderr_write("DEBUG INFO: ", "string");
        self::$interp->stderr_write(" current instruction order: ", "string");
        
        $order = self::$interp->get_current_order();
        self::$interp->stderr_write($order, "int");
    } 
}