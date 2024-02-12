<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

use IPP\Student\Exception\SemanticException;
use IPP\Student\Constants;
use IPP\Student\Argument\LabelArgument;
use IPP\Student\Argument\SymbArgument;
use IPP\Student\Argument\TypeArgument;
use IPP\Student\Argument\VarArgument;

class Instruction
{
    private $order;
    private $opCode;
    private $execute_func;
    private $args;

    final public function __construct($order, $opCode, $args)
    {
        $this->order    = $order;
        $this->opCode   = $opCode;

        $arg_types = Constants::getInstance()->get_argument_types($opCode);

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

        $this->execute_func = Constants::getInstance()->get_execute_func($opCode);
    }

    public function execute()
    {
        $func = $this->execute_func;

        $arg1 = $this->args[0];

        $func([$arg1->get_value()]);
    }
}
