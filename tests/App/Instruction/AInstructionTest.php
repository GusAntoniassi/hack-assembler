<?php
namespace App\Test\Instruction;

use App\Exception\EmptyInstructionException;
use App\Exception\InvalidInstructionException;
use App\Instruction\AInstruction;
use App\LookupTable\SymbolTable;
use PHPUnit\Framework\TestCase;

class AInstructionTest extends TestCase
{
    private $symbolTable;

    protected function setUp()
    {
        $this->symbolTable = new SymbolTable();
    }

    public function testGetBinaryCodeWithNewVariable()
    {
        $this->symbolTable->lookupOrSet('foo');

        $instruction = new AInstruction('@foo', $this->symbolTable);

        // Primeira variável é sempre armazenada na posição 16
        $expected = '0000000000010000';

        $result = $instruction->getBinaryCode();

        $this->assertEquals($expected, $result);
    }

    public function testGetBinaryCodeWithSecondNewVariable()
    {
        $this->symbolTable->lookupOrSet('foo');
        $this->symbolTable->lookupOrSet('bar');

        $instruction = new AInstruction('@bar', $this->symbolTable);

        // Segunda variável seria armazenada na posição 17
        $expected = '0000000000010001';

        $result = $instruction->getBinaryCode();

        $this->assertEquals($expected, $result);
    }

    public function testGetBinaryCodeWithDefinedVariable()
    {
        $this->symbolTable->set('foo', 255);

        $instruction = new AInstruction('@foo', $this->symbolTable);

        // 255 em binário
        $expected = '0000000011111111';

        $result = $instruction->getBinaryCode();

        $this->assertEquals($expected, $result);
    }

    public function testGetBinaryCodeWithDefaultVariable()
    {
        $instruction = new AInstruction('@R1', $this->symbolTable);

        $expected = '0000000000000001';

        $result = $instruction->getBinaryCode();

        $this->assertEquals($expected, $result);
    }

    public function testGetBinaryCodeWithRegularNumber()
    {
        $instruction = new AInstruction('@1', $this->symbolTable);

        $expected = '0000000000000001';

        $result = $instruction->getBinaryCode();

        $this->assertEquals($expected, $result);
    }

    public function testGetBinaryCodeThrowsExceptionOnNumberTooBig()
    {
        $instruction = new AInstruction('@32768', $this->symbolTable);
        $this->expectException(InvalidInstructionException::class);

        $instruction->getBinaryCode();
    }

    public function testGetBinaryCodeThrowsExceptionOnNegativeNumber()
    {
        $instruction = new AInstruction('@-123', $this->symbolTable);
        $this->expectException(InvalidInstructionException::class);

        $instruction->getBinaryCode();
    }

    public function testgetBinaryCodeWithEmptyInstructionThrowsEmptyInstructionException() {
        $this->expectException(EmptyInstructionException::class);
        $instruction = new AInstruction('', $this->symbolTable);
    }
}