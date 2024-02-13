<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

use Closure;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Constants;
use IPP\Student\Argument\LabelArgument;
use IPP\Student\Argument\SymbArgument;
use IPP\Student\Argument\TypeArgument;
use IPP\Student\Argument\VarArgument;

class Instruction
{
    private int $order;
    private string $opCode;
    private Closure $execute_func;

    /** @var array<LabelArgument|SymbArgument|TypeArgument|VarArgument>  */
    private array $args;

    /** 
     * @param array<int|string|bool> $args
    */
    final public function __construct(int $order, string $opCode, array $args)
    {
        $this->order    = $order;
        $this->opCode   = $opCode;

        $arg_types = Constants::get_instance()->get_argument_types($opCode);

        if (count($args) != count($arg_types)) 
            throw new SemanticException("Wrong argument count for instruction: $opCode");

        // Iterate over argument types and instantiate objects based on the types
        foreach ($arg_types as $index => $arg_type) 
        {
            if (!isset($args[$index])) 
                throw new SemanticException("Missing argument for type: $arg_type");

            // Instantiate object based off the argument type
            $this->args[] = new $arg_type($args[$index]);
        }

        $this->execute_func = Constants::get_instance()->get_execute_func($opCode);
    }

    public function execute(): void
    {
        $func = $this->execute_func;

        $arg1 = $this->args[0];

        $func([$arg1->get_value()]);
    }
}
