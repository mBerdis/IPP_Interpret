<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandValueException;
use IPP\Student\Exception\OperandTypeException;

class ADD_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "int" || $type2 !== "int") 
            throw new OperandTypeException("ADD: Operand type error! Expected int, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = $data1 + $data2;
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class SUB_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "int" || $type2 !== "int") 
            throw new OperandTypeException("SUB: Operand type error! Expected int, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = $data1 - $data2;
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class MUL_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "int" || $type2 !== "int") 
            throw new OperandTypeException("MUL: Operand type error! Expected int, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = $data1 * $data2;
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "int");
    } 
}

class IDIV_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "int" || $type2 !== "int") 
            throw new OperandTypeException("IDIV: Operand type error! Expected int, got $type1 and $type2");

        if ($data2 === 0) 
            throw new OperandValueException("Division by zero!");

        $arg1 = $this->args[0];
        $val = intdiv($data1, $data2);
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

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);
    
        if ($type1 !== $type2) 
            throw new OperandTypeException("LT operand type mismatch $type1 and $type2!");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) < self::get_arg_data($this->args[2]);
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

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== $type2) 
            throw new OperandTypeException("GT operand type mismatch $type1 and $type2!");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) > self::get_arg_data($this->args[2]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class EQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== $type2) 
            throw new OperandTypeException("EQ operand type mismatch! $type1 and $type2");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) === self::get_arg_data($this->args[2]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class AND_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "bool" || $type2 !== "bool") 
            throw new OperandTypeException("AND: Operand type error! Expected bool, got $type1 and $type2");

        $arg1 = $this->args[0];
        $val = self::get_arg_data($this->args[1]) and self::get_arg_data($this->args[2]);
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $val, "bool");
    } 
}

class OR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");

        $type1 = self::get_arg_type($this->args[1]);
        $type2 = self::get_arg_type($this->args[2]);

        if ($type1 !== "bool" || $type2 !== "bool") 
            throw new OperandTypeException("OR: Operand type error! Expected bool, got $type1 and $type2");

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

        $type = self::get_arg_type($this->args[2]);
        if ($type !== "bool") 
            throw new OperandTypeException("OR: Operand type error! Expected bool, got $type");
        
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
        $val = chr(self::get_arg_data($this->args[1]));
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