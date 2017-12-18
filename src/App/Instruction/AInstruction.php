<?php
namespace App\Instruction;

class AInstruction extends Instruction {
    private $instruction;
    private $number;
    const MAX_NUM = 32766;

    public function __construct($instruction) {
        $this->instruction = $instruction;
        
        $number = substr($instruction, 1);
        $this->number = (int) $number;
    }

    public function toString() {
        return $this->toBinary($this->number);
    }

    private function toBinary($num) {
        if ($num > self::MAX_NUM) {
            throw new Exception\InvalidInstructionException($this->instruction, 'Valor acima do permitido');
        } else if ($num < 0) {
            throw new Exception\InvalidInstructionException($this->instruction, 'Número negativo não permitido');
        }

        $binaryNum = (string) decbin($num);

        return str_pad($binaryNum, 16, '0', STR_PAD_LEFT);
    }
}