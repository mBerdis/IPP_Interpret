<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Memory;
use IPP\Student\Instruction\AbstractInstruction;

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