<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Flow;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;

class JUMPIFEQ_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "label");
        $label = $this->args[0]->get_value();
        $order = self::$interp->find_label($label);

        if ($this->args[1]->is_nil() || $this->args[2]->is_nil())
            throw new OperandTypeException("JUMPIFEQ nil operand type!");

        $data1 = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);
        $data2 = self::get_arg_data($this->args[2]);
        $type2 = self::get_arg_type($this->args[2]);
           
        if ($type1 !== $type2)
            throw new OperandTypeException("JUMPIFEQ operand type mismatch! $type1 !== $type2");

        if ($data1 === $data2)
            self::$interp->set_current_order($order);
    } 
}