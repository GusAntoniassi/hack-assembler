<?php
/**
 * Interface para as instruções da Hack Assembly Language
 */
namespace App\Instruction;

interface InstructionInterface
{

    /**
     * Retorna a representação binária (hack) para a instrução atual
     *
     * @return string
     */
    public function getBinaryCode(): string;
}