<?php
namespace App\Test\LookupTable;

use App\LookupTable\SymbolTable;
use PHPUnit\Framework\TestCase;

class SymbolTableTest extends TestCase
{
    private $symbolTable;

    protected function setUp()
    {
        $this->symbolTable = new SymbolTable();
    }

    /**
     * @dataProvider providerSymbolTableDefaultValues
     */
    public function testSymbolTableIsInitializedWithDefaultValues($key, $expectedValue)
    {
        $result = $this->symbolTable->lookup($key);

        $this->assertEquals($expectedValue, $result);
    }

    public function testSetKeyFirstAndThenLookup()
    {
        $expected = 255;

        $this->symbolTable->set('foo', $expected);

        $result = $this->symbolTable->lookup('foo');

        $this->assertEquals($expected, $result);
    }

    public function testLookupOrSetWithNewVariable()
    {
        // Primeira variável será definida na posição 16
        $expected = 16;

        $result = $this->symbolTable->lookupOrSet('foo');

        $this->assertEquals($expected, $result);
    }

    public function testLookupOrSetWithExistingVariable()
    {
        $expected = $this->symbolTable->lookupOrSet('foo');

        $result = $this->symbolTable->lookupOrSet('foo');

        $this->assertEquals($expected, $result);
    }

    public function providerSymbolTableDefaultValues()
    {
        return [
            ['R0', '0'],
            ['R1', '1'],
            ['R2', '2'],
            ['R3', '3'],
            ['R4', '4'],
            ['R5', '5'],
            ['R6', '6'],
            ['R7', '7'],
            ['R8', '8'],
            ['R9', '9'],
            ['R10', '10'],
            ['R11', '11'],
            ['R12', '12'],
            ['R13', '13'],
            ['R14', '14'],
            ['R15', '15'],
            ['SCREEN', '16384'],
            ['KBD', '24576'],
            ['SP', '0'],
            ['LCL', '1'],
            ['ARG', '2'],
            ['THIS', '3'],
            ['THAT', '4']
        ];
    }
}