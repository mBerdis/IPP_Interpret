<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Argument;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandValueException;
use IPP\Student\Exception\OperandTypeException;

class LABEL_Instruction extends AbstractInstruction
{
    /** @param array<Argument> $args */
    public function __construct(int $order, string $opCode, array $args)
    {
        parent::__construct($order, $opCode, $args);

        // add label to array, this is in constructor because it needs to be done only once!
        self::check_arg_type($this->args[0], "label");
    
        $label = $this->args[0]->get_value();
        self::$interp->add_label($label, $this->order);
    }

    public function execute(): void 
    {
        // NOP
        return;
    } 
}

class JUMP_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "label");

        $label = $this->args[0]->get_value();
        $order = self::$interp->find_label($label);

        self::$interp->set_current_order($order);
    }
}

class JUMPIFEQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "label");

        if ($this->args[1]->is_nil() || $this->args[2]->is_nil())
            throw new OperandTypeException("JUMPIFEQ nil operand type!");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);
           
        if ($type1 !== $type2)
            throw new OperandTypeException("JUMPIFEQ operand type mismatch! $type1 !== $type2");

        if ($data1 === $data2)
        {
            $label = $this->args[0]->get_value();
            $order = self::$interp->find_label($label);
            self::$interp->set_current_order($order);
        }
    } 
}

class JUMPIFNEQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "label");

        if ($this->args[1]->is_nil() || $this->args[2]->is_nil())
            throw new OperandTypeException("JUMPIFNEQ nil operand type!");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);
           
        if ($type1 !== $type2)
            throw new OperandTypeException("JUMPIFNEQ operand type mismatch!");

        if ($data1 !== $data2)
        {
            $label = $this->args[0]->get_value();
            $order = self::$interp->find_label($label);
            self::$interp->set_current_order($order);
        }
    } 
}

class EXIT_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        $val = self::get_arg_data($this->args[1]);
        
        if ($val < 0 || $val > 9) 
            throw new OperandValueException("Wrong operand value for EXIT!");

        self::$interp->set_exit_code($val);
        self::$interp->set_halt(true);
    } 
}