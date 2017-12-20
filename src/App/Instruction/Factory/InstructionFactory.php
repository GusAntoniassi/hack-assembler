<?php
namespace App\Instruction\Factory;

use App\Instruction;
use App\LookupTable\SymbolTable;

class InstructionFactory
{

    public function getInstruction($instruction, SymbolTable $symbolTable): Instruction\InstructionInterface
    {
        if ($instruction[0] === '@') {
            return new Instruction\AInstruction($instruction, $symbolTable);
        } else {
            return new Instruction\CInstruction($instruction);
        }
    }
}