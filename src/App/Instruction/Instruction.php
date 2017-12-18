<?php
namespace App\Instruction;

abstract class Instruction {
    /**
     * @param string $instruction
     */
    public static function getInstruction($instruction) {
        if ($instruction[0] === '@') {
            return new AInstruction($instruction);
        } else {
            return new CInstruction($instruction);
        }
    }

    public abstract function toString();
}