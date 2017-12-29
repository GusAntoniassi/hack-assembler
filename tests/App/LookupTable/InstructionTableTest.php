<?php
namespace App\Test\LookupTable;

use App\Exception\EmptyInstructionException;
use App\Exception\UndefinedInstructionException;
use App\LookupTable\InstructionTable;
use PHPUnit\Framework\TestCase;

class InstructionTableTest extends TestCase
{
    private $instructionTable;

    protected function setUp()
    {
        $this->instructionTable = new InstructionTable();
    }

    /**
     * @dataProvider providerInstructionTableWithCompInstructions
     */
    public function testLookupValidCompInstructions($instruction, $expectedBinaryOutput)
    {
        $result = $this->instructionTable->lookup('comp', $instruction);

        $this->assertEquals($expectedBinaryOutput, $result);
    }

    /**
     * @dataProvider providerInstructionTableWithDestInstructions
     */
    public function testLookupValidDestInstructions($instruction, $expectedBinaryOutput)
    {
        $result = $this->instructionTable->lookup('dest', $instruction);

        $this->assertEquals($expectedBinaryOutput, $result);
    }

    /**
     * @dataProvider providerInstructionTableWithJumpInstructions
     */
    public function testLookupValidJumpInstructions($instruction, $expectedBinaryOutput)
    {
        $result = $this->instructionTable->lookup('jump', $instruction);

        $this->assertEquals($expectedBinaryOutput, $result);
    }

    public function testLookupEmptyCompInstructionThrowsEmptyInstructionException()
    {
        $this->expectException(EmptyInstructionException::class);
        $this->instructionTable->lookup('comp', '');
    }

    public function testLookupUndefinedCompInstructionThrowsUndefinedInstructionException()
    {
        $this->expectException(UndefinedInstructionException::class);
        $this->instructionTable->lookup('comp', 'foobar');
    }

    public function testLookupUndefinedDestInstructionThrowsUndefinedInstructionException()
    {
        $this->expectException(UndefinedInstructionException::class);
        $this->instructionTable->lookup('dest', 'foobar');
    }

    public function testLookupUndefinedJumpInstructionThrowsUndefinedInstructionException()
    {
        $this->expectException(UndefinedInstructionException::class);
        $this->instructionTable->lookup('jump', 'foobar');
    }

    public function testLookupUndefinedTableThrowsUndefinedInstructionException() {
        $this->expectException(UndefinedInstructionException::class);
        $this->instructionTable->lookup('foo', 'bar');
    }

    public function providerInstructionTableWithCompInstructions()
    {
        return [
            ['0', '0101010'],
            ['1', '0111111'],
            ['-1', '0111010'],
            ['D', '0001100'],
            ['A', '0110000'],
            ['M', '1110000'],
            ['!D', '0001101'],
            ['!A', '0110001'],
            ['!M', '1110001'],
            ['-D', '0001111'],
            ['-A', '0110011'],
            ['D+1', '0011111'],
            ['A+1', '0110111'],
            ['M+1', '1110111'],
            ['D-1', '0001110'],
            ['A-1', '0110010'],
            ['M-1', '1110010'],
            ['D+A', '0000010'],
            ['D+M', '1000010'],
            ['D-A', '0010011'],
            ['D-M', '1010011'],
            ['A-D', '0000111'],
            ['M-D', '1000111'],
            ['D&A', '0000000'],
            ['D&M', '1000000'],
            ['D|A', '0010101'],
            ['D|M', '1010101'],
        ];
    }

    public function providerInstructionTableWithDestInstructions()
    {
        return [
            ['', '000'],
            ['M', '001'],
            ['D', '010'],
            ['MD', '011'],
            ['A', '100'],
            ['AM', '101'],
            ['AD', '110'],
            ['AMD', '111'],
        ];
    }

    public function providerInstructionTableWithJumpInstructions()
    {
        return [
            ['', '000'],
            ['JGT', '001'],
            ['JEQ', '010'],
            ['JGE', '011'],
            ['JLT', '100'],
            ['JNE', '101'],
            ['JLE', '110'],
            ['JMP', '111'],
        ];
    }
}