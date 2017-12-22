<?php
/**
 * Modela uma tabela de símbolos
 * 
 * Utilizada para armazenar e buscar os números de linhas representado pelas
 * labels, as posições de memória das variáveis, tanto as definidas pelo usuário
 * quanto as definidas por padrão na especificação da Hack Language.
 */
namespace App\LookupTable;

class SymbolTable
{
    /**
     * @var array
     */
    private $symbolTable = [];

    /**
     * @var integer
     */
    private $nextVariablePointer;

    public function __construct()
    {
        // Add the standard values R0, R1, R2...
        for ($i = 0; $i < 16; $i++) {
            $this->symbolTable["R$i"] = "$i";
        }

        $this->symbolTable['SCREEN'] = '16384';
        $this->symbolTable['KBD'] = '24576';
        $this->symbolTable['SP'] = '0';
        $this->symbolTable['LCL'] = '1';
        $this->symbolTable['ARG'] = '2';
        $this->symbolTable['THIS'] = '3';
        $this->symbolTable['THAT'] = '4';

        $this->nextVariablePointer = 16;
    }

    /**
     * Busca por uma chave na tabela
     *
     * @param string $key
     * @return string|boolean Retorna FALSE se não encontrar
     */
    public function lookup($key)
    {
        return $this->symbolTable[$key] ?? false;
    }

    /**
     * Define uma chave na tabela
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->symbolTable[$key] = $value;
    }

    /**
     * Verifica se uma variável já está definida na tabela, e se ainda não estiver
     * define-a na próxima posição disponível. Retorna o valor da tabela em todas
     * as ocasiões
     *
     * @param string $key
     * @return integer
     */
    public function lookupOrSet($key)
    {
        $lookup = $this->lookup($key);
        if ($lookup === FALSE) {
            $this->set($key, $this->nextVariablePointer);
            return $this->nextVariablePointer++;
        }

        return $lookup;
    }
}