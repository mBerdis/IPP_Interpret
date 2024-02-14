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

abstract class AbstractInstruction
{
    protected int $order;
    protected string $opCode;

    /** @var array<LabelArgument|SymbArgument|TypeArgument|VarArgument>  */
    protected array $args;

    /** 
     * @param array<int|string|bool> $args
     * @param array<string> $arg_types An array of class names as strings
    */
    protected function __construct(int $order, string $opCode, array $args, array $arg_types)
    {
        $this->order    = $order;
        $this->opCode   = $opCode;

        // Instantiate objects based on class names in $arg_types
        foreach ($arg_types as $index => $className) {
            // Check if the index exists in $args to avoid errors
            $argValue = isset($args[$index]) ? $args[$index] : null;

            #TODO: throw exception

            // Instantiate the class using the class name and pass the value from $args
            $this->args[] = new $className($argValue);
        }
    }

    abstract public function execute(): void;
}
