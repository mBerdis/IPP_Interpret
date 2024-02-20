<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

require_once '/ipp-php/student/Instruction/MemoryInstructions.php';
require_once '/ipp-php/student/Exception/IntrepreterExceptions.php';
use DOMDocument;
use DOMElement;
use DOMXpath;
use IPP\Core\AbstractInterpreter;
use IPP\Core\Exception\XMLException;
use IPP\Student\Argument;
use IPP\Student\Exception\SourceStructureException;
use IPP\Student\Exception\SemanticException;
use IPP\Student\Exception\FrameAccessException;
use IPP\Student\Exception\OperandTypeException;
use IPP\Student\Exception\VariableAccessException;
use IPP\Student\Instruction\AbstractInstruction;
use IPP\Student\Instruction\InstructionFactory;

class Interpreter extends AbstractInterpreter
{
    // array of opcodes used for validation
    private const OP_CODES = [
        "MOVE", "CREATEFRAME", "PUSHFRAME", "POPFRAME", "DEFVAR", "CALL",
        "RETURN", "PUSHS", "POPS", "ADD", "SUB", "MUL", "IDIV", "LT", 
        "GT", "EQ", "AND", "OR", "NOT", "INT2CHAR", "STRI2INT", "READ", 
        "WRITE", "CONCAT", "STRLEN", "GETCHAR", "SETCHAR", "TYPE", "LABEL", 
        "JUMP", "JUMPIFEQ", "JUMPIFNEQ", "EXIT", "DPRINT", "BREAK"
    ];

    private const array ARG_TYPES  = ["var", "symb", "label", "type", "bool", "string", "int", "nil"];
    private const string ARG_REGEX = "/arg[1-9]+[0-9]*/";

    /** @var array<string, VariableData>  */
    private array $GF;        // varName (key) -> VariableData (value)

    /** @var array<string, VariableData>  */
    private ?array $TF = NULL;        // varName (key) -> VariableData (value)

    /** @var array<array<string, VariableData>> */
    private array $frameStack;

    /** @var array<int> */
    private array $callStack;

    /** @var array<VariableData> */
    private array $dataStack;

    /** @var array<string, int>  */
    private array $labels;    // name (key) -> instrOrder (value)

    private int  $exit_code     = 0;        // set by EXIT instruction
    private bool $halt          = false;    // will stop interpreting when true
    private int  $currentOrder;             // used for flow control of the interpreted program

    protected function init(): void
    {
        parent::init();

        $this->GF           = array();
        $this->labels       = array();
        $this->frameStack   = array();
        $this->callStack    = array();
        $this->dataStack    = array();
    }

    /** @param array<int> &$orders */
    private function validate_instruction_node(DOMElement &$node, array &$orders): void
    {
        if ($node->nodeName !== "instruction")
            throw new SourceStructureException("Expected instruction node!");

        if (!in_array(strtoupper($node->getAttribute("opcode")), $this::OP_CODES))
            throw new XMLException("Unknown opcode!");

        $order = intval($node->getAttribute("order")); // Cast to integer
        if ($order < 0)
            throw new SourceStructureException("Negative instruction order!");

        if (in_array($order, $orders))
            throw new SourceStructureException("Instruction order duplicity!");

        # add instruction`s order to the array
        $orders[] = $order;
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
                    continue;
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

    /** @return array<Argument> */
    private function get_args(DOMElement &$instruction): array 
    {
        $args = array();

        foreach ($instruction->childNodes as $arg)
        {
            if ($arg->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
                continue;

            $value = trim($arg->nodeValue);
            if ($arg instanceof DOMElement) 
            {
                $type  = $arg->getAttribute("type");
                switch ($type) {
                    case "int":
                        $value = intval($value);
                        break;
                    case "bool":
                        $value = boolval($value);
                        break;
                    default:
                        break;
                }
                $args[] = new Argument($value, $type);
            }
        }

        return $args;
    }

    public function execute(): int
    {
        $dom = $this->source->getDOMDocument();
        $xpath = new DOMXpath($dom);

        $this->validate_xml_attrs($dom, $xpath);

        $instructions = iterator_to_array($xpath->evaluate('/program/instruction'));

        // set reference so Instructions can call Interpret`s methods
        AbstractInstruction::setInterpreter($this);

        $instructionList = array();

        // parse xml into Instruction objects
        foreach ($instructions as $instruction) {
            $opCode = strtoupper($instruction->getAttribute("opcode"));
            $order  = $instruction->getAttribute("order");
            $args   = $this->get_args($instruction);
            // instruction factory will return constructed child of AbstractInstruction based on the opCode given.
            $instructionList[$order] = InstructionFactory::create_Instruction($order, $opCode, $args);
        }

        // array of instruction orders, orders are in non-descending order. They dont have to be evenly spaced!
        $instKeys = array_keys($instructionList);

        for ($i = 0; ($i < count($instKeys)) && !$this->halt; $i++)     // i is used for getting correct order
        { 
            $this->currentOrder = $instKeys[$i];
            $instructionList[$this->currentOrder]->execute();
            $i = array_search($this->currentOrder, $instKeys);  // update $i, jump could change $currentOrder
        }

        return $this->exit_code;
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
                    throw new VariableAccessException("Undefined variable $varName in GF!");
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

    public function get_variable_type(string $frame, string $varName): string
    {
        $variable = $this->get_variable($frame, $varName);
        switch ($variable->get_type()) 
        {
            case DataType::INT:
                $type = "int";
                break;
            case DataType::STRING:
                $type = "string";
                break;
            case DataType::BOOL:
                $type = "bool";
                break;
            case DataType::NIL:
                $type = "nil";
                break;

            default:
                $type = "";
                break;
        }
        return $type;
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

    public function stdout_write(int|string|bool $msg, string $type): void 
    {
        switch ($type) {
            case "int":
                $this->stdout->writeInt($msg);
                break;

            case "nil":
                $this->stdout->writeString("");
                break;

            case "bool":
                $this->stdout->writeBool($msg);
                break;

            case "float":
                $this->stdout->writeFloat(floatval($msg));
                break;

            default:
                // replace escaped seq with corresponding chars
                $msg = preg_replace_callback('/\\\\(\d{3})/', function ($matches) {
                    return chr(intval($matches[1]));
                }, $msg);

                $this->stdout->writeString($msg);
                break;
        }
    }

    public function stderr_write(int|string|bool $msg, string $type): void 
    {
        switch ($type) {
            case "int":
                $this->stderr->writeInt($msg);
                break;

            case "nil":
                $this->stderr->writeString("");
                break;

            case "bool":
                $this->stderr->writeBool($msg);

            case "float":
                $this->stderr->writeFloat(floatval($msg));
                break;

            default:
                // replace escaped seq with corresponding chars
                $msg = preg_replace_callback('/\\\\(\d{3})/', function ($matches) {
                    return chr(intval($matches[1]));
                }, $msg);

                $this->stderr->writeString($msg);
                break;
        }
    }

    public function set_exit_code(int $exitCode): void 
    {
        $this->exit_code = $exitCode;   
    }

    public function set_halt(bool $halt): void 
    {
        $this->halt = $halt;
    }

    public function set_current_order(int $order): void 
    {
        $this->currentOrder = $order;
    }

    public function get_current_order(): int 
    {
        return $this->currentOrder;
    }

    public function push_call(): void 
    {
        array_push($this->callStack, $this->currentOrder);
    }

    public function pop_call(): int 
    {
        return array_pop($this->callStack);
    }

    public function push_data(int|string|bool $value, string $type): void 
    {
        $storage = new VariableData();
        $dataType = $this->type_from_str($type);
        $storage->set_var($value, $dataType);
        
        array_push($this->dataStack, $storage);
    }

    public function pop_data(): VariableData
    {
        return array_pop($this->dataStack);
    }
}
