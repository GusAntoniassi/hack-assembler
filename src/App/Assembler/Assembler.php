<?php
namespace App\Assembler;

use App\Instruction;
use App\LookupTable\SymbolTable;
use App\Stream\IOStream;

class Assembler
{
    private $output;
    private $symbolTable;

    public function assemble(IOStream $file)
    {
        $this->symbolTable = new SymbolTable();

        $this->output = $this->loadFile($file);
        $this->parseLabels();
        $this->parseToBinary();


        return $this->output;
    }
    
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

    private function findNextInstructionNumber($currentLineNumber, $instructions) {
        for ($i = $currentLineNumber; $i < count($instructions); $i++) {
            $line = $instructions[$i];
            if (!empty($line) && $line[0] !== '(') {
                return $i;
            }
        }

        // Returns false if the next valid line is not found
        return false;
    }

    private function parseLabels() {
        $lines = explode(PHP_EOL, $this->output);

        $lineNumber = 0;
        foreach ($lines as $line) {
            if ($line[0] === '(') {
                // Remove the parenthesis
                $label = substr($line, 1, -1);
                // Find the next instruction that is not a label
                $nextLine = $this->findNextInstructionNumber($lineNumber, $lines);
                $this->symbolTable->set($label, $nextLine);
            } else {
                $lineNumber++;
            }
        }
    }

    private function parseToBinary() {
        $lines = explode(PHP_EOL, $this->output);

        $output = '';
        $instructionFactory = new Instruction\Factory\InstructionFactory();
        
        foreach ($lines as $line) {
            $line = trim($line);

            $commentStart = strpos($line, '//');
            if ($commentStart !== FALSE) {
                $line = trim(substr($line, 0, $commentStart));
            }

            if (empty($line))
                continue;

            // TODO: Verificar se a instrução é uma variável (@FOO), verificar se já está na tabela e se não estiver inserir
            // Talvez eu precise usar algum esquema tipo containers pra fazer essa parte, já que o ideal seria a A-instruction
            // fazer o lookup na tabela sozinha

            $instruction = $instructionFactory->getInstruction($line);

            $output .= $instruction->getBinaryCode() . "\r\n";
        }

        $this->output = $output;
    }
}