<?php
/**
 * Carro-chefe do programa, carrega as linhas de um IOStream na memória, inicializa
 * as labels na LookupTable e converte as instruções em binário
 */
namespace App\Assembler;

use App\Instruction;
use App\LookupTable\SymbolTable;
use App\Stream\IOStream;

// Andre Noel passou por aqui

class Assembler
{
    /**
     * @var string
     */
    private $output;
    /**
     * @var SymbolTable
     */
    private $symbolTable;

    /**
     * Faz todas as operações no arquivo e retorna sua representação em binário
     * @param IOStream $file
     * @return string
     */
    public function assemble(IOStream $file)
    {
        $this->symbolTable = new SymbolTable();

        $this->output = $this->loadFile($file);
        $this->parseLabels();

        $this->parseToBinary();

        return $this->output;
    }

    /**
     * Lê um arquivo e retorna-o ignorando linhas em branco e com todos os comentários
     * removidos
     *
     * @param type $fileStream
     * @return type
     */
    private function loadFile($fileStream) {
        $output = '';
        
        while (!$fileStream->isEOF()) {
            $line = trim($fileStream->readLine());

            $commentStart = strpos($line, '//');
            if ($commentStart !== FALSE) {
                $line = trim(substr($line, 0, $commentStart));
            }

            if (empty($line))
                continue;

            $output .= $line . PHP_EOL;
        }

        $output = trim($output, PHP_EOL);

        return $output;
    }

    /**
     * Preenche a LookupTable com as posições de suas respectivas linhas iniciais
     * para serem utilizadas depois em instruções JUMP
     */
    private function parseLabels() {
        if (empty($this->output)) {
            return $this->output;
        }
        
        $lines = explode(PHP_EOL, $this->output);

        $lineNumber = 0;
        $output = '';

        foreach ($lines as $line) {
            if ($line[0] === '(') {
                // Remover os parênteses
                $label = substr($line, 1, -1);
                $nextLine = $lineNumber;
                $this->symbolTable->set($label, $nextLine);
            } else {
                $output .= $line . PHP_EOL;
                $lineNumber++;
            }
        }

        $this->output = trim($output, PHP_EOL);
    }

    /**
     * Converte todas as instruções do arquivo em binário
     */
    private function parseToBinary() {
        if (empty($this->output)) {
            return $this->output;
        }

        $lines = explode(PHP_EOL, $this->output);

        $output = '';
        $instructionFactory = new Instruction\Factory\InstructionFactory();

        foreach ($lines as $line) {
            $line = trim($line);

            $instruction = $instructionFactory->getInstruction($line, $this->symbolTable);

            $output .= $instruction->getBinaryCode() . "\r\n";
        }

        $this->output = $output;
    }
}
