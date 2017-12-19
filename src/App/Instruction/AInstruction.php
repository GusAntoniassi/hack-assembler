<?php
namespace App\Instruction;

class AInstruction implements InstructionInterface
{
    private $instruction;
    private $number;

    const MAX_NUM = 32766;

    public function __construct($instruction)
    {
        $this->instruction = $instruction;

        $number = substr($instruction, 1);
        $this->number = (int) $number;
    }

    public function getBinaryCode()
    {
        if ($this->number > self::MAX_NUM) {
            throw new Exception\InvalidInstructionException($this->instruction, 'Valor acima do permitido');
        } else if ($this->number < 0) {
            throw new Exception\InvalidInstructionException($this->instruction, 'Número negativo não permitido');
        }

        $binaryNum = (string) decbin($this->number);

        return str_pad($binaryNum, 16, '0', STR_PAD_LEFT);
    }
}