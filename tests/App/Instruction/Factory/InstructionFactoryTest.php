<?php

namespace App\Instruction\Factory;

use App\Exception\EmptyInstructionException;
use App\Instruction\AInstruction;
use App\Instruction\CInstruction;
use App\LookupTable\SymbolTable;
use PHPUnit\Framework\TestCase;

class InstructionFactoryTest extends TestCase {
    private $instructionFactory;
    private $symbolTable;

    protected function setUp() {
        $this->instructionFactory = new InstructionFactory();
        $this->symbolTable = new SymbolTable();
    }

    public function testInstructionFactoryReturnsAInstructionForInstructionWithAmpersand()
    {
        $symbolTable = new SymbolTable();

        $result = $this->instructionFactory->getInstruction('@123', $this->symbolTable);
        $resultClass = get_class($result);

        $expectedClass = AInstruction::class;

        $this->assertEquals($expectedClass, $resultClass);
    }

    public function testInstructionFactoryReturnsCInstructionForInstructionWithoutAmpersand()
    {
        $result = $this->instructionFactory->getInstruction('0;JMP', $this->symbolTable);
        $resultClass = get_class($result);

        $expectedClass = CInstruction::class;

        $this->assertEquals($expectedClass, $resultClass);
    }

    public function testIntructionFactoryThrowsEmptyInstructionExceptionForEmptyInstruction()
    {
        $this->expectException(EmptyInstructionException::class);
        $this->instructionFactory->getInstruction('', $this->symbolTable);
    }
}