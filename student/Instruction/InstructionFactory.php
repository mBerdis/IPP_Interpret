<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

require_once '/ipp-php/student/Instruction/FlowInstructions.php';
require_once '/ipp-php/student/Instruction/MemoryInstructions.php';
require_once '/ipp-php/student/Instruction/MiscInstructions.php';
require_once '/ipp-php/student/Instruction/StringInstructions.php';
require_once '/ipp-php/student/Instruction/DataStackInstructions.php';
use IPP\Core\Exception\XMLException;
use IPP\Student\Argument;

class InstructionFactory
{
    // maps opCode to correct AbstractInstructions`s child
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
        "ADD"           => \IPP\Student\Instruction\Arithmetic\ADD_Instruction::class,
        "SUB"           => \IPP\Student\Instruction\Arithmetic\SUB_Instruction::class,
        "MUL"           => \IPP\Student\Instruction\Arithmetic\MUL_Instruction::class,
        "IDIV"          => \IPP\Student\Instruction\Arithmetic\IDIV_Instruction::class,
        "LT"            => \IPP\Student\Instruction\Arithmetic\LT_Instruction::class,
        "GT"            => \IPP\Student\Instruction\Arithmetic\GT_Instruction::class,
        "EQ"            => \IPP\Student\Instruction\Arithmetic\EQ_Instruction::class,
        "AND"           => \IPP\Student\Instruction\Arithmetic\AND_Instruction::class,
        "OR"            => \IPP\Student\Instruction\Arithmetic\OR_Instruction::class,
        "NOT"           => \IPP\Student\Instruction\Arithmetic\NOT_Instruction::class,
        "INT2CHAR"      => \IPP\Student\Instruction\Arithmetic\INT2CHAR_Instruction::class,
        "STRI2INT"      => \IPP\Student\Instruction\Arithmetic\STRI2INT_Instruction::class,
    
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
