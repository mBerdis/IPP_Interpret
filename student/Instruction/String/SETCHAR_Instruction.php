<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

 namespace IPP\Student\Instruction\String;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\StringOpException;

class SETCHAR_Instruction extends AbstractInstruction
{
    public function execute(): void 
    {
        self::check_arg_type($this->args[0], "var");
        $type0 = self::get_arg_type($this->args[0]);

        $index = self::get_arg_data($this->args[1]);
        $type1 = self::get_arg_type($this->args[1]);

        $replStr = self::get_arg_data($this->args[2]);
        $type2   = self::get_arg_type($this->args[2]);

        if ($type0 !== "string" || $type1 !== "int" || $type2 !== "string") 
            throw new OperandTypeException("SETCHAR: Operand type error! Expected string, int and string, got $type0, $type1 and $type2");

        $arg1  = $this->args[0];
        $str   = self::get_arg_data($arg1);

        if (!isset($str[$index]) || !isset($replStr[0]) || $index < 0)
            throw new StringOpException("SETCHAR: index out of bounds!");

        // replace escaped seq with corresponding chars
        $replStr = preg_replace_callback('/\\\\(\d{3})/', function ($matches) {
            return chr(intval($matches[1]));
        }, $replStr);

        $str[$index] = $replStr[0];
        self::$interp->update_variable($arg1->get_frame(), $arg1->get_value(), $str, "string");
    }
}