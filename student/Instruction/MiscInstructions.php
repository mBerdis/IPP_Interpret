<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Instruction\AbstractInstruction;

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
        $toWrite = self::get_arg_data($this->args[0]);
        $type = self::get_arg_type($this->args[0]);

        self::$interp->stdout_write($toWrite, $type);
    } 
}

class TYPE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type = self::get_arg_type($this->args[1]);
        $arg = $this->args[0];

        self::$interp->update_variable($arg->get_frame(), $arg->get_value(), $type, "string");
    } 
}

class DPRINT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $toWrite = self::get_arg_data($this->args[0]);
        $type = self::get_arg_type($this->args[0]);

        self::$interp->stderr_write($toWrite, $type);
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