<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

require_once '/ipp-php/student/Instruction/MemoryInstructions.php';
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

    /** @var array<string, string>  */
    private array $GF;        // name (key) -> Variable (value)

    /** @var array<string, int>  */
    private array $labels;    // name (key) -> instrOrder (value)

    protected function init(): void
    {
        parent::init();

        $this->GF       = array();
        $this->labels   = array();
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

    public function execute(): int
    {
        $dom = $this->source->getDOMDocument();
        $xpath = new DOMXpath($dom);

        $this->validate_xml_attrs($dom, $xpath);

        $instructions = iterator_to_array($xpath->evaluate('/program/instruction'));

        # DEBUG
        foreach ($instructions as $instruction) {
            $opCode = $instruction->getAttribute("opcode");
            $order  = $instruction->getAttribute("order");
            // $this->stdout->writeString($instruction->getAttribute("opcode") . " " . $instruction->getAttribute("order") . "\n");
        }
        
        //$this->add_label("hi", 8);
        //$this->stdout->writeString($this->find_label("hi") . "\n");

        $i = new Move_Inst(1, "MOVE", ["ahfg", 7]);
        $i->execute();

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

}
