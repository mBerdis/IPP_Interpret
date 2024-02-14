<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Student\Exception\SemanticException;
use IPP\Student\Constants;
use IPP\Student\Argument\LabelArgument;
use IPP\Student\Argument\SymbArgument;
use IPP\Student\Argument\TypeArgument;
use IPP\Student\Argument\VarArgument;
use IPP\Student\Instruction\AbstractInstruction;

class Move_Inst extends AbstractInstruction
{
    /** @param array<int|string|bool> $args */
    final public function __construct(int $order, string $opCode, array $args)
    {
        $arg_types = [VarArgument::class, SymbArgument::class];
        parent::__construct($order, $opCode, $args, $arg_types);
    }

    public function execute(): void 
    {
        echo($this->args[0]->get_value());
        echo($this->args[1]->get_value());
        echo("MOVE instruction %d\n");
    } 
}
