<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

require_once '/ipp-php/student/Instruction/MemoryInstructions.php';
require_once '/ipp-php/student/Exception/IntrepreterExceptions.php';
use DOMDocument;
use DOMNode;
use DOMElement;
use IPP\Core\AbstractInterpreter;
use IPP\Core\ReturnCode;
use DOMXpath;
use IPP\Core\Exception\XMLException;
use IPP\Student\Exception\SourceStructureException;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Instruction\Move_Inst;
use IPP\Student\Argument;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\VariableAccessException;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Instruction\InstructionFactory;

class Interpreter extends AbstractInterpreter
{
    private const OP_CODES = [
        "MOVE", "CREATEFRAME", "PUSHFRAME", "POPFRAME", "DEFVAR", "CALL",
        "RETURN", "PUSHS", "POPS", "ADD", "SUB", "MUL", "IDIV", "LT", 
        "GT", "EQ", "AND", "OR", "NOT", "INT2CHAR", "STRI2INT", "READ", 
        "WRITE", "CONCAT", "STRLEN", "GETCHAR", "SETCHAR", "TYPE", "LABEL", 
        "JUMP", "JUMPIFEQ", "JUMPIFNEQ", "EXIT", "DPRINT", "BREAK"
    ];

    private const array ARG_TYPES  = ["var", "symb", "label", "type", "bool", "string", "int"];
    private const string ARG_REGEX = "/arg[1-9]+[0-9]*/";

    /** @var array<string, VariableData>  */
    private array $GF;        // varName (key) -> VariableData (value)

    /** @var array<string, VariableData>  */
    private ?array $TF = NULL;        // varName (key) -> VariableData (value)

    /** @var array<array<string, VariableData>> */
    private array $frameStack;

    /** @var array<string, int>  */
    private array $labels;    // name (key) -> instrOrder (value)

    protected function init(): void
    {
        parent::init();

        $this->GF           = array();
        $this->labels       = array();
        $this->frameStack   = array();
    }

    /** @param array<int> &$orders */
    private function validate_instruction_node(DOMElement &$node, array &$orders): void
    {
        if ($node->nodeName !== "instruction")
            throw new SourceStructureException("Expected instruction node!");

        if (!in_array($node->getAttribute("opcode"), $this::OP_CODES))
            throw new XMLException("Unknown opcode!");

        if ($node->getAttribute("order") < 0)
            throw new SourceStructureException("Negative instruction order!");

        if (in_array($node->getAttribute("order"), $orders))
            throw new SourceStructureException("Instruction order duplicity!");

        # add instruction`s order to the array
        $orders[] = $node->getAttribute("order");
    }

    private function validate_arg_node(DOMElement &$arg): void
    {
        if (!preg_match(self::ARG_REGEX, $arg->nodeName))
            throw new SourceStructureException("Expected arg node!");

        if (!in_array($arg->getAttribute("type"), $this::ARG_TYPES))
            throw new XMLException("Unknown argument type!");
    }

    private function validate_xml_attrs(DOMDocument &$dom, DOMXpath &$xpath): void
    {
        if ($dom->documentElement->getAttribute("language") != "IPPcode24")
            throw new XMLException("Wrong language attribute of program!");

        $childrenNodes = $dom->documentElement->childNodes;
        $orders = [];

        # check if xml nodes are valid
        foreach ($childrenNodes as $node) 
        {
            if ($node->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
                continue;
            $this->validate_instruction_node($node, $orders);
            foreach ($node->childNodes as $arg)
            {
                if ($arg->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
                    return;
                $this->validate_arg_node($arg);
            }
        }

        // if program is here, nodes are valid.

        // sort instructions
        $instructions = iterator_to_array($xpath->evaluate('/program/instruction'));
        usort($instructions, static function($a, $b) {
            $orderA = intval($a->getAttribute('order'));
            $orderB = intval($b->getAttribute('order'));
            
            if ($orderA === $orderB) return 0;
            return ($orderA < $orderB) ? -1 : 1;
        });

        // insert sorted instructions back to the $dom
        foreach ($instructions as $instruction) 
            $dom->documentElement->appendChild($instruction);
    }

    private function get_args(DOMElement &$instruction): array 
    {
        $args = array();

        foreach ($instruction->childNodes as $arg)
        {
            if ($arg->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
                continue;

            $value = trim($arg->nodeValue);
            $type  = $arg->getAttribute("type");
            $args[] = new Argument($value, $type);
        }

        return $args;
    }

    public function execute(): int
    {
        $dom = $this->source->getDOMDocument();
        $xpath = new DOMXpath($dom);

        $this->validate_xml_attrs($dom, $xpath);

        $instructions = iterator_to_array($xpath->evaluate('/program/instruction'));

        $instructionList = array();
        foreach ($instructions as $instruction) {
            $opCode = $instruction->getAttribute("opcode");
            $order  = $instruction->getAttribute("order");
            $args   = $this->get_args($instruction);
            $instructionList[] = InstructionFactory::create_Instruction($order, $opCode, $args);
        }

        AbstractInstruction::setInterpreter($this);

        $this->add_label("hi", 8);
        //$this->stdout->writeString($this->find_label("hi") . "\n");

        foreach ($instructionList as $instruction)
        {
            $instruction->execute();
        }

        // $val = $this->input->readString();
        // $this->stdout->writeString("stdout\n");
        // $this->stderr->writeString("stderr\n");

        return ReturnCode::OK;
    }

    public function add_label(string $label, int $instrOrder): void
    {
        $this->labels[$label] = $instrOrder;
    }

    public function find_label(string $label): int
    {
        if (!array_key_exists($label, $this->labels))
            throw new SemanticException("Unknown label!");

        return $this->labels[$label];
    }

    public function add_variable(string $frame, string $varName): void
    {
        switch ($frame) {
            case "GF":
                if (array_key_exists($varName, $this->GF))
                    throw new SemanticException("Variable $varName redefinition!");
                $this->GF[$varName] = new VariableData();
                break;
            
            case "TF":
                if (!$this->TF)
                    throw new FrameAccessException("TF does not exist!");
                if (array_key_exists($varName, $this->TF))
                    throw new SemanticException("Variable $varName redefinition!");
                $this->TF[$varName] = new VariableData();
                break;

            case "LF":
                if (empty($this->frameStack))
                    throw new FrameAccessException("LF does not exist!");

                $LF = end($this->frameStack);
                if (array_key_exists($varName, $LF))
                    throw new SemanticException("Variable $varName redefinition!");
                $LF[$varName] = new VariableData();
                break;

            default:
                throw new FrameAccessException();
        }
    }

    private function get_variable(string $frame, string $varName): VariableData 
    {
        switch ($frame) {
            case "GF":
                if (!array_key_exists($varName, $this->GF))
                    throw new VariableAccessException();
                return $this->GF[$varName];
            
            case "TF":
                if (!$this->TF)
                    throw new FrameAccessException("TF does not exist!");
                if (!array_key_exists($varName, $this->TF))
                    throw new VariableAccessException("Variable $varName doesnt exist in TF!");
                return $this->TF[$varName];

            case "LF":
                if (empty($this->frameStack))
                    throw new FrameAccessException("LF does not exist!");

                $LF = end($this->frameStack);
                if (!array_key_exists($varName, $LF))
                    throw new VariableAccessException("Variable $varName doesnt exist in LF!");
                return $LF[$varName];

            default:
                throw new FrameAccessException();
        }
    }

    public function get_variable_data(string $frame, string $varName): int|string|bool
    {
        $variable = $this->get_variable($frame, $varName);
        return $variable->get_value();
    }

    public function update_variable(string $frame, string $varName, int|string|bool $value, string $type): void 
    {
        $varType = $this->type_from_str($type);
        $variable = $this->get_variable($frame, $varName);
        $variable->set_var($value, $varType);
    }

    public function read_to_var(string $frame, string $varName, string $type): void
    {
        $val = "";
        switch ($type) {
            case "bool":
                $val = $this->input->readBool();
                break;

            case "int":
                $val = $this->input->readInt();
                break;

            case "string":
                $val = $this->input->readString();
                break;
            
            default:
                throw new OperandTypeException();
        }

        if (is_null($val))
        {
            $type = "nil";
            $val  = "nil";
        }

        $this->update_variable($frame, $varName, $val, $type);
    }

    public function create_frame(): void
    {
        $this->TF = [];
    }

    public function push_frame(): void
    {
        array_push($this->frameStack, $this->TF);
        $this->TF = NULL;
    }

    public function pop_frame() : void 
    {
        if (!end($this->frameStack))
            throw new FrameAccessException("Trying to pop non-existing frame!");

        $this->TF = array_pop($this->frameStack);
    }

    private function type_from_str(string $str): DataType
    {
        switch ($str) {
            case "int":
                return DataType::INT;
            case "string":
                return DataType::STRING;
            case "bool":
                return DataType::BOOL;
            case "nil":
                return DataType::NIL;
            default:
                return DataType::UNDEFINED;
        }
    }
}
