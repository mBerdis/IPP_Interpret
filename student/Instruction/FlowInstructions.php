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

class LABEL_Instruction extends AbstractInstruction
{
    /** @param array<Argument> $args */
    public function __construct(int $order, string $opCode, array $args)
    {
        parent::__construct($order, $opCode, $args);

        self::check_arg_type($this->args[0], "label");
    
        $label = $this->args[0]->get_value();
        self::$interp->add_label($label, $this->order);
    }

    public function execute(): void 
    {
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

        if ($this->args[1]->is_var())
        {
            $data1 = self::$interp->get_variable_data($this->args[1]->get_frame(), $this->args[1]->get_value());
            $type1 = self::$interp->get_variable_type($this->args[1]->get_frame(), $this->args[1]->get_value());
        }
        else 
        {
            $data1 = $this->args[1]->get_value();
            $type1 = $this->args[1]->get_type();
        }

        if ($this->args[2]->is_var())
        {
            $data2 = self::$interp->get_variable_data($this->args[2]->get_frame(), $this->args[2]->get_value());
            $type2 = self::$interp->get_variable_type($this->args[2]->get_frame(), $this->args[2]->get_value());
        }
        else 
        {
            $data2 = $this->args[2]->get_value();
            $type2 = $this->args[2]->get_type();
        }
           
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

        if ($this->args[1]->is_var())
        {
            $data1 = self::$interp->get_variable_data($this->args[1]->get_frame(), $this->args[1]->get_value());
            $type1 = self::$interp->get_variable_type($this->args[1]->get_frame(), $this->args[1]->get_value());
        }
        else 
        {
            $data1 = $this->args[1]->get_value();
            $type1 = $this->args[1]->get_type();
        }

        if ($this->args[2]->is_var())
        {
            $data2 = self::$interp->get_variable_data($this->args[2]->get_frame(), $this->args[2]->get_value());
            $type2 = self::$interp->get_variable_type($this->args[2]->get_frame(), $this->args[2]->get_value());
        }
        else 
        {
            $data2 = $this->args[2]->get_value();
            $type2 = $this->args[2]->get_type();
        }
           
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
        if ($this->args[0]->is_var())
            $val = self::$interp->get_variable_data($this->args[0]->get_frame(), $this->args[0]->get_value());
        else
            $val = $this->args[0]->get_value();

        if ($val < 0 || $val > 9) 
            throw new OperandValueException("Wrong operand value for EXIT!");

        self::$interp->set_exit_code($val);
        self::$interp->set_halt(true);
    } 
}