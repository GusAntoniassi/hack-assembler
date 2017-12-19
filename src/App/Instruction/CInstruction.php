<?php
namespace App\Instruction;

use App\Exception;

class CInstruction implements InstructionInterface {
    private $instruction;
    
    private $dest = '';
    private $comp = '';
    private $jump = '';

    private $lookupTable;

    public function __construct($instruction) {
        $this->instruction = $instruction;
        $this->lookupTable = new LookupTable();

        $comp = $instruction;

        $posEquals = strpos($comp, '=');
        if ($posEquals !== FALSE) {
            $this->dest = substr($comp, 0, $posEquals);
            $comp = substr($comp, $posEquals+1);
        }

        $posSemicolon = strpos($comp, ';');
        if ($posSemicolon !== FALSE) {
            $this->jump = substr($comp, $posSemicolon+1);
            $comp = substr($comp, 0, $posSemicolon);
        }

        $this->comp = $comp;
    }

    public function getBinaryCode() {
        $inst = '111';
        $inst .= $this->lookupTable->lookup('comp', $this->comp);
        $inst .= $this->lookupTable->lookup('dest', $this->dest);
        $inst .= $this->lookupTable->lookup('jump', $this->jump);

        return $inst;
    }
}