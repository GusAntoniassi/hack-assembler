<?php
namespace App\Assembler;

use App\Instruction;

class Assembler {
    public function assemble($file) {
        $lineNumber = 0;
        $instructionFactory = new Instruction\Factory\InstructionFactory();
        
        while (!feof($file)) {
            $line = trim(fgets($file));

            $commentStart = strpos($line, '//');
            if ($commentStart !== FALSE) {
                $line = trim(substr($line, 0, $commentStart));
            }
            
            if (empty($line)) continue;

            $lineNumber++;

            $instruction = $instructionFactory->getInstruction($line);

            echo $instruction . "\n";
        }

        fclose($file);
    }
}