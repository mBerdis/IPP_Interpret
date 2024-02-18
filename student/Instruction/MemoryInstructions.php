<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Instruction\AbstractInstruction;

class MOVE_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "symb");
        
        $arg1 = $this->args[0];
        $arg2 = $this->args[1];

        if ($arg1->is_var()) 
            $toCopy = self::$interp->get_variable_data($arg2->get_frame(), $arg2->get_value());
        else 
            $toCopy = $arg2->get_value();

        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $toCopy, $arg2->get_type());
    } 
}

class CREATEFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->create_frame();
    } 
}

class PUSHFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->push_frame();
    } 
}

class POPFRAME_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::$interp->pop_frame();
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
        self::check_arg_type($this->args[0], "label");
        self::$interp->push_call();

        $label = $this->args[0]->get_value();
        $order = self::$interp->find_label($label);

        self::$interp->set_current_order($order);
    } 
}

class RETURN_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $order = self::$interp->pop_call();
        self::$interp->set_current_order($order);
    } 
}