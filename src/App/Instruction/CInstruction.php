<?php
namespace App\Instruction;

use App\Exception;

class CInstruction extends Instruction {
    private $instruction;
    
    private $dest = '';
    private $comp = '';
    private $jump = '';

    private $lookupTable;

    public function __construct($instruction) {
        $this->instruction = $instruction;
        $this->lookupTable = new LookupTable();

        $this->comp = $instruction;
//        if (strpos($instruction, '=') !== -1) {
//
//        }
    }

    public function toString() {
        $inst = '111';
        $inst .= $this->lookupTable->lookup('comp', $this->comp);
        $inst .= $this->lookupTable->lookup('dest', $this->dest);
        $inst .= $this->lookupTable->lookup('jump', $this->jump);

        return $inst;
    }
}