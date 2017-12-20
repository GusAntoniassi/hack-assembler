<?php
namespace App\LookupTable;

class SymbolTable
{
    private $symbolTable = [];
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
     * @param string $key
     * @return mixed Returns the value from the table, or false if not found
     */
    public function lookup($key) {
        return $this->symbolTable[$key] ?? false;
    }

    public function set($key, $value) {
        $this->symbolTable[$key] = $value;
    }

    public function lookupOrSet($key) {
        $lookup = $this->lookup($key);
        if ($lookup === FALSE){
            $this->set($key, $this->nextVariablePointer);
            return $this->nextVariablePointer++;
        }

        return $lookup;
    }

    /** Debug only **/
    public function getTable() {
        return $this->symbolTable;
    }
}