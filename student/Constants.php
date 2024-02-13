<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

use Closure;
use IPP\Core\Exception\XMLException;
use IPP\Student\Argument\LabelArgument;
use IPP\Student\Argument\SymbArgument;
use IPP\Student\Argument\TypeArgument;
use IPP\Student\Argument\VarArgument;

class Constants
{
    private static ?Constants $instance = null;

    /** @var array<string, Closure>  */
    private array $INST_EXECUTES;

    private const string ARGUMENT_NAMESPACE = "IPP\\Student\\Argument\\";

    /** @var array<string, array<string>>  */
    private array $INST_ARG_TYPES = [
        // Work with memory frames
        "MOVE" 			=> ["VarArgument", "SymbArgument"],
        "CREATEFRAME" 	=> [],
        "PUSHFRAME" 	=> [],
        "POPFRAME" 		=> [],
        "DEFVAR" 		=> ["VarArgument"],
        "CALL" 			=> ["LabelArgument"],
        "RETURN" 		=> [],

        // Data stack
        "PUSHS" 		=> ["SymbArgument"],
        "POPS" 			=> ["VarArgument"],

        // Arithmetic
        "ADD" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "SUB" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "MUL" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "IDIV" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "LT" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "GT" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "EQ" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "AND" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "OR" 			=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "NOT" 			=> ["VarArgument", "SymbArgument"],
        "INT2CHAR" 		=> ["VarArgument", "SymbArgument"],
        "STRI2INT" 		=> ["VarArgument", "SymbArgument", "SymbArgument"],

        // I/O
        "READ" 			=> ["VarArgument", "TypeArgument"],
        "WRITE" 		=> ["SymbArgument"],
        
        // Strings
        "CONCAT" 		=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "STRLEN" 		=> ["VarArgument", "SymbArgument"],
        "GETCHAR" 		=> ["VarArgument", "SymbArgument", "SymbArgument"],
        "SETCHAR" 		=> ["VarArgument", "SymbArgument", "SymbArgument"],

        // Type
        "TYPE" 			=> ["VarArgument", "SymbArgument"],

        // Flow control
        "LABEL" 		=> ["LabelArgument"],
        "JUMP" 			=> ["LabelArgument"],
        "JUMPIFEQ" 		=> ["LabelArgument", "SymbArgument", "SymbArgument"],
        "JUMPIFNEQ" 	=> ["LabelArgument", "SymbArgument", "SymbArgument"],
        "EXIT" 			=> ["SymbArgument"],

        // Debug
        "DPRINT" 		=> ["SymbArgument"],
        "BREAK" 		=> []
    ];

    private function __construct()
    {
        $this->INST_EXECUTES["MOVE"] = function($args)
        {
            Constants::get_instance();
            echo($args[0] . "\n");
        };
        $this->INST_EXECUTES["ADD"] = function($args){echo($args[0] . "\n");};
        $this->INST_EXECUTES["WRITE"] = function($args){echo($args[0] . "\n");};
        
    }

    public static function get_instance(): Constants
    {
      if (self::$instance == null)
        self::$instance = new Constants();
   
      return self::$instance;
    }

    public function get_execute_func(string $opCode): callable
    {
        if (!array_key_exists($opCode, $this->INST_EXECUTES))
            throw new XMLException("Unknown opcode!");
        return $this->INST_EXECUTES[$opCode];
    }

    /** @return array<string> */
    public function get_argument_types(string $opCode): array
    {
        if (!array_key_exists($opCode, $this->INST_ARG_TYPES))
            throw new XMLException("Unknown opcode!");

        // Iterate over each entry in $this->INST_ARG_TYPES[$opCode] and append the namespace
        $argumentTypes = array_map(function ($type) {
            return self::ARGUMENT_NAMESPACE . $type;
        }, $this->INST_ARG_TYPES[$opCode]);

        return $argumentTypes;
    }
}
