<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student\Instruction;

require_once '/ipp-php/student/Exception/IntrepreterExceptions.php';
use IPP\Student\Constants;
use IPP\Student\Argument;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Interpreter;

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
        if (!isset($arg) || $arg->get_type() !== $type) 
            throw new OperandTypeException();
    }

    public function get_order(): int 
    {
        return $this->order;
    }
}
