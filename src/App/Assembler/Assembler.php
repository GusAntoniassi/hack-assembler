<?php
namespace App\Assembler;

use App\Instruction;
use App\Stream\IOStream;

class Assembler
{
    private $output;

    public function assemble(IOStream $file)
    {

        $this->output = $this->loadFile($file);
        $this->parseToBinary();

        return $this->output;
    }
    
    private function loadFile($fileStream) {
        $output = '';
        
        while (!$fileStream->isEOF()) {
            $line = $fileStream->readLine();
            $output .= $line . PHP_EOL;
        }

        return $output;
    }

    private function parseToBinary() {
        $lines = explode(PHP_EOL, $this->output);

        $output = '';
        $lineNumber = 0;
        $instructionFactory = new Instruction\Factory\InstructionFactory();
        
        foreach ($lines as $line) {
            $line = trim($line);

            $commentStart = strpos($line, '//');
            if ($commentStart !== FALSE) {
                $line = trim(substr($line, 0, $commentStart));
            }

            if (empty($line))
                continue;

            $lineNumber++;

            $instruction = $instructionFactory->getInstruction($line);

            $output .= $instruction->getBinaryCode() . "\r\n";
        }

        $this->output = $output;
    }
}