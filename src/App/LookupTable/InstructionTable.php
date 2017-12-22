<?php
/**
 * Modela uma tabela de instruções pré-definida na especificação da Hack Language
 *
 * A tabela consiste de valores para os comp bits, dest bits e jump bits.
 * Referência para a tabela: https://i.imgur.com/dRM4y0J.png
 */
namespace App\LookupTable;

use App\Exception;

class InstructionTable
{
    /**
     * @var array
     */
    private $compTable;

    /**
     * @var array
     */
    private $destTable;

    /**
     * @var array
     */
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

    /**
     * Procura por uma instrução na tabela e retorna sua representação em binário
     *
     * @param string $tableName
     * @param string $inst
     * @return string
     * @throws Exception\UndefinedInstructionException Caso a instrução não seja encontrada em sua respectiva tabela
     */
    public function lookup($tableName, $inst)
    {
        $lookupResult = $this->tablesLookup($tableName, $inst);
        if ($lookupResult === FALSE) {
            throw new Exception\UndefinedInstructionException($inst);
        }

        return $lookupResult;
    }

    /**
     * Método interno, retorna o valor do lookup em determinada tabela
     *
     * Foi necessário separar cada lookup pois a tabela do comp exige um
     * processamento adicional.
     *
     * @param string $tableName
     * @param string $inst
     * @return string|boolean Retorna FALSE se a instrução não for encontrada na tabela
     */
    private function tablesLookup($tableName, $inst)
    {
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

    /**
     * Procura pela instrução na tabela comp
     *
     * As instruções comp não podem estar vazias, portanto uma exceção é lançada
     * caso isso ocorra. O primeiro bit da instrução indicará ao processador qual
     * registrador de memória será utilizado para a instrução. Quando o primeiro
     * bit for 0, ele utilizará o A-Register, e quando for 1 ele utilizará o
     * M-Register. Para converter a instrução para binário basta verificar se a
     * instrução possui o caractere 'M'.
     *
     * @param string $inst
     * @return string|boolean Retorna FALSE se a instrução não for encontrada na tabela
     * @throws Exception\InvalidInstructionException
     */
    private function compLookup($inst)
    {
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

    /**
     * Procura pela instrução na tabela dest
     *
     * @param string $inst
     * @return string|boolean Retorna FALSE se a instrução não for encontrada na tabela
     */
    private function destLookup($inst)
    {
        return $this->destTable[$inst] ?? false;
    }

    /**
     * Procura pela instrução na tabela jump
     *
     * @param string $inst
     * @return string|boolean Retorna FALSE se a instrução não for encontrada na tabela
     */
    private function jumpLookup($inst)
    {
        return $this->jumpTable[$inst] ?? false;
    }
}