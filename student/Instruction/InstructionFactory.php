<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

require_once '/ipp-php/student/Instruction/ArithmeticInstructions.php';
require_once '/ipp-php/student/Instruction/FlowInstructions.php';
require_once '/ipp-php/student/Instruction/MemoryInstructions.php';
require_once '/ipp-php/student/Instruction/MiscInstructions.php';
require_once '/ipp-php/student/Instruction/StringInstructions.php';
use IPP\Core\Exception\XMLException;
use IPP\Student\Argument;

class InstructionFactory
{
    /** @var array<string, string>  */
    private static array $INST_CLASSES = [
        // Work with memory frames
        "MOVE"          => MOVE_Instruction::class,
        "CREATEFRAME"   => CREATEFRAME_Instruction::class,
        "PUSHFRAME"     => PUSHFRAME_Instruction::class,
        "POPFRAME"      => POPFRAME_Instruction::class,
        "DEFVAR"        => DEFVAR_Instruction::class,
        "CALL"          => CALL_Instruction::class,
        "RETURN"        => RETURN_Instruction::class,
    
        // Data stack
        "PUSHS"         => PUSHS_Instruction::class,
        "POPS"          => POPS_Instruction::class,
    
        // Arithmetic
        "ADD"           => ADD_Instruction::class,
        "SUB"           => SUB_Instruction::class,
        "MUL"           => MUL_Instruction::class,
        "IDIV"          => IDIV_Instruction::class,
        "LT"            => LT_Instruction::class,
        "GT"            => GT_Instruction::class,
        "EQ"            => EQ_Instruction::class,
        "AND"           => AND_Instruction::class,
        "OR"            => OR_Instruction::class,
        "NOT"           => NOT_Instruction::class,
        "INT2CHAR"      => INT2CHAR_Instruction::class,
        "STRI2INT"      => STRI2INT_Instruction::class,
    
        // I/O
        "READ"          => READ_Instruction::class,
        "WRITE"         => WRITE_Instruction::class,
    
        // Strings
        "CONCAT"        => CONCAT_Instruction::class,
        "STRLEN"        => STRLEN_Instruction::class,
        "GETCHAR"       => GETCHAR_Instruction::class,
        "SETCHAR"       => SETCHAR_Instruction::class,
    
        // Type
        "TYPE"          => TYPE_Instruction::class,
    
        // Flow control
        "LABEL"         => LABEL_Instruction::class,
        "JUMP"          => JUMP_Instruction::class,
        "JUMPIFEQ"      => JUMPIFEQ_Instruction::class,
        "JUMPIFNEQ"     => JUMPIFNEQ_Instruction::class,
        "EXIT"          => EXIT_Instruction::class,
    
        // Debug
        "DPRINT"        => DPRINT_Instruction::class,
        "BREAK"         => BREAK_Instruction::class
    ];

    /** @param array<Argument> $args */
    public static function create_Instruction(int $order, string $opCode, array $args): AbstractInstruction
    {
        if (!array_key_exists($opCode, self::$INST_CLASSES))
            throw new XMLException("Unknown opcode!");

        $instructionClass = self::$INST_CLASSES[$opCode];
        return new $instructionClass($order, $opCode, $args);
    }
}
