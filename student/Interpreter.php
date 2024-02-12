<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\ReturnCode;
use DOMXpath;
use IPP\Core\Exception\XMLException;
use IPP\Student\Exception\SourceStructureException;
use IPP\Student\Exception\SemanticException;

class Interpreter extends AbstractInterpreter
{
    private const OP_CODES = [
        "MOVE", "CREATEFRAME", "PUSHFRAME", "POPFRAME", "DEFVAR", "CALL",
        "RETURN", "PUSHS", "POPS", "ADD", "SUB", "MUL", "IDIV", "LT", 
        "GT", "EQ", "AND", "OR", "NOT", "INT2CHAR", "STRI2INT", "READ", 
        "WRITE", "CONCAT", "STRLEN", "GETCHAR", "SETCHAR", "TYPE", "LABEL", 
        "JUMP", "JUMPIFEQ", "JUMPIFNEQ", "EXIT", "DPRINT", "BREAK"
    ];

    private const ARG_TYPES = ["var", "symb", "label", "type", "bool", "string", "int"];
    private const ARG_REGEX = "/arg[1-9]+[0-9]*/";

    private $GF;        // name (key) -> Variable (value)
    private $labels;    // name (key) -> instrOrder (value)

    protected function init(): void
    {
        parent::init();

        $this->GF       = array();
        $this->labels   = array();
    }

    private function validate_instruction_node($node, &$orders)
    {
        if ($node->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
            return;

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

    private function validate_arg_node($arg)
    {
        if ($arg->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
            return;

        if (!preg_match(self::ARG_REGEX, $arg->nodeName))
            throw new SourceStructureException("Expected arg node!");

        if (!in_array($arg->getAttribute("type"), $this::ARG_TYPES))
            throw new XMLException("Unknown argument type!");
    }

    private function validate_xml_attrs($dom, $xpath)
    {
        if ($dom->documentElement->getAttribute("language") != "IPPcode24")
            throw new XMLException("Wrong language attribute of program!");

        $childrenNodes = $dom->documentElement->childNodes;
        $orders = [];

        # check if xml nodes are valid
        foreach ($childrenNodes as $node) 
        {
            $this->validate_instruction_node($node, $orders);
            foreach ($node->childNodes as $arg)
                $this->validate_arg_node($arg);
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
            // $this->stdout->writeString($instruction->getAttribute("opcode") . " " . $instruction->getAttribute("order") . "\n");
        }
        
        //$this->add_label("hi", 8);
        //$this->stdout->writeString($this->find_label("hi") . "\n");

        $i = new Instruction(1, "MOVE", [5, 5]);
        $i->execute();

        $i2 = new Instruction(2, "ADD", [15454, 5, 45]);
        $i2->execute();

        $i3 = new Instruction(3, "WRITE", [2]);
        $i3->execute();


        // $val = $this->input->readString();
        // $this->stdout->writeString("stdout\n");
        // $this->stderr->writeString("stderr\n");

        return ReturnCode::OK;
    }

    public function add_label($label, $instrOrder)
    {
        $this->labels[$label] = $instrOrder;
    }

    public function find_label($label): int
    {
        if (!array_key_exists($label, $this->labels))
            throw new SemanticException("Unknown label!");

        return $this->labels[$label];
    }

}
