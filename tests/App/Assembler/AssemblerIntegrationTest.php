<?php
namespace App\Test\Assembler;

use App\Assembler\Assembler;
use App\Stream\IOStream;
use PHPUnit\Framework\TestCase;

class AssemblerIntegrationTest extends TestCase
{
    /**
     * @var Assembler
     */
    private $assembler;

    protected function setUp() {
        $this->assembler = new Assembler();
    }

    public function testAssemblerWithProgram($programPath)
    {
        $expected = $this->getHackCompareFile($programPath);

        $asmFile = $this->getAsmProgramFile($programPath);
        
        $result = $this->assembler->assemble($asmFile);

        $this->expectEquals($expected, $result);
    }

    private function providerTestFiles() {
        $testFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR;

        $filesDir = [
            ['add/Add']
        ];

        // TODO: Substituir a / por DIRECTORY_SEPARATOR de forma programável e retornar
    }

    private function getAsmProgramFile($programPath) : IOStream {
        $file = new IOStream($programPath . '.asm', 'r');

        return $file;
    }

    private function getHackCompareFile($programPath) : IOStream {
        $file = new IOStream($programPath . '.hack', 'r');
        
        return $file;
    }
}