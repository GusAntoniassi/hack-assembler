<?php
namespace App\Instruction\Factory;

use App\Instruction;

class InstructionFactory
{

    public function getInstruction($instruction): Instruction\InstructionInterface
    {
        if ($instruction[0] === '@') {
            return new Instruction\AInstruction($instruction);
        } else {
            return new Instruction\CInstruction($instruction);
        }
    }
}