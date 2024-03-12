<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction\Flow;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Argument;

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