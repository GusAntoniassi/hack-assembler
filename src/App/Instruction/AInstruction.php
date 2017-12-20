<?php
namespace App\Instruction;

use App\Exception;
use App\LookupTable\SymbolTable;

class AInstruction implements InstructionInterface
{
    private $instruction;
    private $number;
    private $symbolTable;

    const MAX_NUM = 32766;

    public function __construct($instruction, SymbolTable $symbolTable)
    {
        $this->instruction = $instruction;
        $this->symbolTable = $symbolTable;

        $instructionValue = substr($instruction, 1);
        if (is_numeric($instructionValue)) {
            $this->number = (int) $instructionValue;
        } else {
            echo "Instruction: " . $instruction;
            $this->number = (int) $symbolTable->lookupOrSet($instructionValue);
            echo "Number: " . $this->number . PHP_EOL;
        }
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