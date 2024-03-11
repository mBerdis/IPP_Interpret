<?php
/**
 * IPP - IPPcode24 Interpret
 * @author Maroš Berdis (xberdi01)
 */

namespace IPP\Student;

enum DataType
{
    case INT;
    case STRING;
    case BOOL;
    case UNDEFINED;
    case NIL;
};