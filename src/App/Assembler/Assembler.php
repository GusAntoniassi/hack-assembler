<?php
namespace App\Assembler;

use App\Instruction;
use App\Stream\IOStream;

class Assembler
{
    private $output;

    public function assemble(IOStream $file)
    {
        $lineNumber = 0;
        $instructionFactory = new Instruction\Factory\InstructionFactory();

        while (!$file->isEOF()) {
            $line = trim($file->readLine());

            $commentStart = strpos($line, '//');
            if ($commentStart !== FALSE) {
                $line = trim(substr($line, 0, $commentStart));
            }

            if (empty($line))
                continue;

            $lineNumber++;

            $instruction = $instructionFactory->getInstruction($line);

            $this->output .= $instruction->getBinaryCode() . "\r\n";
        }

        return $this->output;
    }
}