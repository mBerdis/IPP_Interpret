<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

use IPP\Core\Exception\XMLException;
use IPP\Student\Argument;

class InstructionFactory
{
    // maps opCode to correct AbstractInstructions`s child
    /** @var array<string, string>  */
    private static array $INST_CLASSES = [
        // Work with memory frames
        "MOVE"          => \IPP\Student\Instruction\Memory\MOVE_Instruction::class,
        "CREATEFRAME"   => \IPP\Student\Instruction\Memory\CREATEFRAME_Instruction::class,
        "PUSHFRAME"     => \IPP\Student\Instruction\Memory\PUSHFRAME_Instruction::class,
        "POPFRAME"      => \IPP\Student\Instruction\Memory\POPFRAME_Instruction::class,
        "DEFVAR"        => \IPP\Student\Instruction\Memory\DEFVAR_Instruction::class,
        "CALL"          => \IPP\Student\Instruction\Memory\CALL_Instruction::class,
        "RETURN"        => \IPP\Student\Instruction\Memory\RETURN_Instruction::class,
    
        // Data stack
        "PUSHS"         => \IPP\Student\Instruction\DataStack\PUSHS_Instruction::class,
        "POPS"          => \IPP\Student\Instruction\DataStack\POPS_Instruction::class,
    
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
        "READ"          => \IPP\Student\Instruction\Misc\READ_Instruction::class,
        "WRITE"         => \IPP\Student\Instruction\Misc\WRITE_Instruction::class,
    
        // Strings
        "CONCAT"        => \IPP\Student\Instruction\String\CONCAT_Instruction::class,
        "STRLEN"        => \IPP\Student\Instruction\String\STRLEN_Instruction::class,
        "GETCHAR"       => \IPP\Student\Instruction\String\GETCHAR_Instruction::class,
        "SETCHAR"       => \IPP\Student\Instruction\String\SETCHAR_Instruction::class,
    
        // Type
        "TYPE"          => \IPP\Student\Instruction\Misc\TYPE_Instruction::class,
    
        // Flow control
        "LABEL"         => \IPP\Student\Instruction\Flow\LABEL_Instruction::class,
        "JUMP"          => \IPP\Student\Instruction\Flow\JUMP_Instruction::class,
        "JUMPIFEQ"      => \IPP\Student\Instruction\Flow\JUMPIFEQ_Instruction::class,
        "JUMPIFNEQ"     => \IPP\Student\Instruction\Flow\JUMPIFNEQ_Instruction::class,
        "EXIT"          => \IPP\Student\Instruction\Flow\EXIT_Instruction::class,
    
        // Debug
        "DPRINT"        => \IPP\Student\Instruction\Misc\DPRINT_Instruction::class,
        "BREAK"         => \IPP\Student\Instruction\Misc\BREAK_Instruction::class
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
