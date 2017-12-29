<?php
/**
 * Utiliza o factory pattern para instanciar uma Instruction a partir de sua
 * representação em string
 *
 * Atualmente na Hack Language existem apenas dois tipos de instruções,
 * A-Instructions e C-Instructions. A-Instructions sempre são antecedidas por
 * um sinal de '@', portanto é utilizado o primeiro caractere para determinar
 * o tipo da instrução.
 */
namespace App\Instruction\Factory;

use App\Instruction;
use App\LookupTable\SymbolTable;

class InstructionFactory
{

    /**
     * Utiliza o primeiro caractere da instrução para determinar se ela é uma
     * A-Instruction ou C-Instruction. Recebe como parâmetro a SymbolTable que
     * foi preenchida na primeira leitura do arquivo para ser consultada nas
     * A-Instruction que dependem de variáveis.
     * 
     * @param string $instruction
     * @param SymbolTable $symbolTable
     * @return \App\Instruction\InstructionInterface
     */
    public function getInstruction($instruction, SymbolTable $symbolTable): Instruction\InstructionInterface
    {
        if (empty($instruction)) {
            throw new \App\Exception\EmptyInstructionException();
        }

        if ($instruction[0] === '@') {
            return new Instruction\AInstruction($instruction, $symbolTable);
        } else {
            return new Instruction\CInstruction($instruction);
        }
    }
}