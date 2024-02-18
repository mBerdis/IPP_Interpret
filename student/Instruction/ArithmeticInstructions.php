<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Exception\SemanticException;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandValueException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringOpException;

class ADD_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "int");
        self::check_arg_type($this->args[2], "int");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() + $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class SUB_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "int");
        self::check_arg_type($this->args[2], "int");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() - $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class MUL_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "int");
        self::check_arg_type($this->args[2], "int");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() * $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class IDIV_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "int");
        self::check_arg_type($this->args[2], "int");

        if ($this->args[2]->get_value() === 0) 
            throw new OperandValueException("Division by zero!");

        $arg1 = $this->args[0];
        $val = intdiv($this->args[1]->get_value(), $this->args[2]->get_value());
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class LT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        if ($this->args[1]->is_nil() || $this->args[2]->is_nil()) 
            throw new OperandTypeException("LT operand is type nil!");

        if ($this->args[1]->get_type() !== $this->args[2]->get_type()) 
            throw new OperandTypeException("LT operand type mismatch!");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() < $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class GT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        if ($this->args[1]->is_nil() || $this->args[2]->is_nil()) 
            throw new OperandTypeException("GT operand is type nil!");

        if ($this->args[1]->get_type() !== $this->args[2]->get_type()) 
            throw new OperandTypeException("GT operand type mismatch!");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() > $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class EQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        if ($this->args[1]->get_type() !== $this->args[2]->get_type()) 
            throw new OperandTypeException("EQ operand type mismatch!");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() === $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class AND_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "bool");
        self::check_arg_type($this->args[2], "bool");

        $arg1 = $this->args[0];
        $val = $this->args[1]->get_value() and $this->args[2]->get_value();
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class OR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        self::check_arg_type($this->args[1], "bool");
        self::check_arg_type($this->args[2], "bool");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) or self::get_arg_data($this->args[2]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class NOT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        
        $arg1 = $this->args[0];
        $val = !self::get_arg_data($this->args[1]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class INT2CHAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $arg1 = $this->args[0];
        $val = chr($this->args[0]->get_value());
        if ($val === "")
            throw new StringOpException("Wrong INT2CHAR value!");
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "string");
    } 
}

class STRI2INT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $str = self::get_arg_data($this->args[1]);
        $pos = self::get_arg_data($this->args[2]);

        $arg1 = $this->args[0];
        $val = ord($str[$pos]);

        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}