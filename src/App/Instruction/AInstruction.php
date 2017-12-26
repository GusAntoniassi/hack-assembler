<?php
/**
 * Modela uma A-Instruction, que é utilizada para setar o registrador do endereço
 * de memória (A-Register) em uma determinada posição
 *
 * Sempre possuirá no comando um número, que pode ser escrito explicitamente ou
 * definido por uma variável. No caso de variáveis, será necessário consultar
 * a SymbolTable para determinar qual a posição de memória que foi reservada para
 * aquela variável.
 */
namespace App\Instruction;

use App\Exception;
use App\LookupTable\SymbolTable;

class AInstruction implements InstructionInterface
{
    /**
     * @var string
     */
    private $instruction;

    /**
     * @var int
     */
    private $number;

    /**
     * @var SymbolTable
     */
    private $symbolTable;

    /**
     * Como os registradores da arquitetura do Hack são de 16 bits, e ele utiliza
     * notação do complemento de 2 para representar os números, o número máximo
     * permitido é (2^(16-1))-1, ou seja, 32767
     */
    const MAX_NUM = 32767;

    /**
     * Verifica se o comando depois do @ é um número ou um texto (nome da variável)
     *
     * Se for um texto, utiliza a SymbolTable para determinar qual a posição de
     * memória que está representando aquela variável
     *
     * @param string $instruction
     * @param SymbolTable $symbolTable
     */
    public function __construct($instruction, SymbolTable $symbolTable)
    {
        $this->instruction = $instruction;
        $this->symbolTable = $symbolTable;

        $instructionValue = substr($instruction, 1);
        if (is_numeric($instructionValue)) {
            $this->number = (int) $instructionValue;
        } else {
            $this->number = (int) $symbolTable->lookupOrSet($instructionValue);
        }
    }

    /**
     * Converte a instrução em um número binário de 16 bits
     *
     * Utiliza a função decbin do PHP para fazer a conversão, e adiciona zeros
     * à esquerda conforme necessário. Na implementação da Hack Language não
     * existe endereçamento para números negativos, e todos os números serão
     * menores ou igual a 32767 (que seria 0111 1111 1111 1111 em binário),
     * portanto não há risco da função decbin retornar um número com mais de 16 bits.
     *
     * @return string
     * @throws Exception\InvalidInstructionException
     */
    public function getBinaryCode() : string
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