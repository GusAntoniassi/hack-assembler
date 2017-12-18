<?php
/**
 * Referência: https://i.imgur.com/dRM4y0J.png
 */
namespace App\Instruction;

use App\Exception;

class LookupTable
{
    private $compTable;
    private $destTable;
    private $jumpTable;

    public function __construct()
    {
        $this->compTable = [
            '0'   => '101010',
            '1'   => '111111',
            '-1'  => '111010',
            'D'   => '001100',
            'A'   => '110000',
            'M'   => '110000',
            '!D'  => '001101',
            '!A'  => '110001',
            '!M'  => '110001',
            '-D'  => '001111',
            '-A'  => '110011',
            'D+1' => '011111',
            'A+1' => '110111',
            'M+1' => '110111',
            'D-1' => '001110',
            'A-1' => '110010',
            'M-1' => '110010',
            'D+A' => '000010',
            'D+M' => '000010',
            'D-A' => '010011',
            'D-M' => '010011',
            'A-D' => '000111',
            'M-D' => '000111',
            'D&A' => '000000',
            'D&M' => '000000',
            'D|A' => '010101',
            'D|M' => '010101',
        ];

        $this->destTable = [
            ''    => '000',
            'M'   => '001',
            'D'   => '010',
            'MD'  => '011',
            'A'   => '100',
            'AM'  => '101',
            'AD'  => '110',
            'AMD' => '111',
        ];

        $this->jumpTable = [
            ''    => '000',
            'JGT' => '001',
            'JEQ' => '010',
            'JGE' => '011',
            'JLT' => '100',
            'JNE' => '101',
            'JLE' => '110',
            'JMP' => '111',
        ];
    }

    public function lookup($tableName, $inst) {
        $lookupResult = $this->tablesLookup($tableName, $inst);
        if ($lookupResult === FALSE) {
            throw new Exception\UndefinedInstructionException($inst);
        }

        return $lookupResult;
    }

    private function tablesLookup($tableName, $inst) {
        switch ($tableName) {
            case 'comp':
                return $this->compLookup($inst);
            case 'dest':
                return $this->destLookup($inst);
            case 'jump':
                return $this->jumpLookup($inst);
            default:
                return false;
        }
    }

    private function compLookup($inst) {
        if ($inst === '') {
            throw new Exception\InvalidInstructionException($this->instruction, 'Instrução "comp" não pode ficar vazia!');
        }

        $lookup = $this->compTable[$inst] ?? false;
        if ($lookup) {
            // Se a instrução acessa o 'M', o primeiro bit do comp é 1, caso contrário o primeiro bit é 0
            if (strpos($inst, 'M') !== FALSE) {
                $lookup = '1' . $lookup;
            } else {
                $lookup = '0' . $lookup;
            }
        }
        return $lookup;
    }
    private function destLookup($inst) {
        return $this->destTable[$inst] ?? false;
    }
    private function jumpLookup($inst) {
        return $this->jumpTable[$inst] ?? false;
    }
}