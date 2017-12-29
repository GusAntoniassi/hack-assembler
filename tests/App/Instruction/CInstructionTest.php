<?php
namespace App\Test\Instruction;

use App\Exception\EmptyInstructionException;
use App\Exception\UndefinedInstructionException;
use App\Instruction\CInstruction;
use PHPUnit\Framework\TestCase;

class CInstructionTest extends TestCase
{

    /**
     * @dataProvider providerGetBinaryCodeWithPossibleInstructionCombinations
     */
    public function testGetBinaryCode($instructionCode, $expectedBinaryOutput)
    {
        $instruction = new CInstruction($instructionCode);

        $result = $instruction->getBinaryCode();

        $this->assertEquals($expectedBinaryOutput, $result);
    }

    public function testGetBinaryCodeWithInvalidInstructionThrowsUndefinedInstructionException()
    {
        $this->expectException(UndefinedInstructionException::class);

        $instruction = new CInstruction('foobar');

        $instruction->getBinaryCode();
    }

    public function testGetBinaryCodeWithEmptyInstructionThrowsEmptyInstructionException()
    {
        $this->expectException(EmptyInstructionException::class);

        $instruction = new CInstruction('');

        $instruction->getBinaryCode();
    }

    public function providerGetBinaryCodeWithPossibleInstructionCombinations()
    {
        return [
            ['AMD=M-1;JLE', '1111110010111110'], // Comp + Dest + Jump
            ['MD=D|A', '1110010101011000'], // Comp + Dest
            ['D&A;JNE', '1110000000000101'], // Comp + Jump
            ['M-1', '1111110010000000'] // Comp
        ];
    }
}