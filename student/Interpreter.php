<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\Exception\InputFileException;
use IPP\Core\ReturnCode;
use DOMXpath;
use IPP\Core\Exception\XMLException;

class Interpreter extends AbstractInterpreter
{
    private const OP_CODES = [
        "MOVE", "CREATEFRAME", "PUSHFRAME", "POPFRAME", "DEFVAR", "CALL",
        "RETURN", "PUSHS", "POPS", "ADD", "SUB", "MUL", "IDIV", "LT", 
        "GT", "EQ", "AND", "OR", "NOT", "INT2CHAR", "STRI2INT", "READ", 
        "WRITE", "CONCAT", "STRLEN", "GETCHAR", "SETCHAR", "TYPE", "LABEL", 
        "JUMP", "JUMPIFEQ", "JUMPIFNEQ", "EXIT", "DPRINT", "BREAK"];

    private function validate_xml_attrs($dom, $xpath)
    {
        #TODO: IPPcode can be case-insensitive
        if ($dom->documentElement->getAttribute("language") != "IPPcode24")
            throw new XMLException("Wrong language attribute of program!");

        $childrenNodes = $dom->documentElement->childNodes;
        $orders = [];

        # check if instructions have correct opCode and order
        foreach ($childrenNodes as $node) 
        {
            if ($node->nodeType !== XML_ELEMENT_NODE)   # skip #text nodes
                continue;

            if ($node->nodeName !== "instruction")
                throw new XMLException("Wrong xml structure!"); # TODO: INVALID_SOURCE_STRUCTURE exception

            if (!in_array($node->getAttribute("opcode"), $this::OP_CODES))
                throw new XMLException("Unknown opcode!");

            if ($node->getAttribute("order") < 0)
                throw new XMLException("Negative instruction order!");  # TODO: INVALID_SOURCE_STRUCTURE exception
        
            if (in_array($node->getAttribute("order"), $orders))
                throw new XMLException("Instruction order duplicity!");  # TODO: INVALID_SOURCE_STRUCTURE exception
            
            # add instruction`s order to the array
            $orders[] = $node->getAttribute("order");
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

        // iterate the sorted array
        foreach ($instructions as $instruction) 
            $dom->documentElement->appendChild($instruction);
    }

    public function execute(): int
    {
        // TODO: Start your code here
        // Check \IPP\Core\AbstractInterpreter for predefined I/O objects:
        $dom = $this->source->getDOMDocument();
        $xpath = new DOMXpath($dom);

        $this->validate_xml_attrs($dom, $xpath);

        $instructions = iterator_to_array($xpath->evaluate('/program/instruction'));

        # DEBUG
        foreach ($instructions as $instruction) {
            $this->stdout->writeString($instruction->getAttribute("opcode") . " " . $instruction->getAttribute("order") . "\n");
        }

        // $val = $this->input->readString();
        // $this->stdout->writeString("stdout\n");
        // $this->stderr->writeString("stderr\n");

        return ReturnCode::OK;
    }
}
