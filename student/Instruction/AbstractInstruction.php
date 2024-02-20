<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

require_once '/ipp-php/student/Exception/IntrepreterExceptions.php';
use IPP\Student\Argument;
use IPP\Student\Interpreter;
use IPP\Student\Exception\OperandTypeException;

abstract class AbstractInstruction
{
    protected static Interpreter $interp; 

    protected int $order;
    protected string $opCode;

    /** @var array<Argument> */
    protected array $args;

    /** @param array<Argument> $args */
    public function __construct(int $order, string $opCode, array $args)
    {
        $this->order    = $order;
        $this->opCode   = $opCode;
        $this->args     = $args;
    }

    public static function setInterpreter(Interpreter $interpreter): void
    {
        self::$interp = $interpreter;
    }

    abstract public function execute(): void;

    protected static function check_arg_type(?Argument &$arg, string $type): void
    {
        if (!isset($arg)) 
        {
            throw new OperandTypeException("Operand type error! Argument not set!");
        }
        if ($arg->get_type() !== $type) 
        {
            $gotType = $arg->get_type();
            $thisClass = static::class;
            throw new OperandTypeException("Operand type error! Expected $type, got $gotType. at $thisClass");
        }     
    }

    protected static function get_arg_data(Argument &$arg): int|bool|string 
    {
        if ($arg->is_var()) 
            $data = self::$interp->get_variable_data($arg->get_frame(), $arg->get_value());
        else
            $data = $arg->get_value();

        return $data;
    }

    protected static function get_arg_type(Argument &$arg): string
    {
        if ($arg->is_var()) 
            $type = self::$interp->get_variable_type($arg->get_frame(), $arg->get_value());
        else
            $type = $arg->get_type();

        return $type;
    }

    public function get_order(): int 
    {
        return $this->order;
    }
}
