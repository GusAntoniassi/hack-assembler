<?php
/**
 * Testa a aplicação com os arquivos de comparação fornecidos pelos autores
 * do curso. Basicamente, se passar esse teste então tem 99% de chance de que
 * o código está funcionando corretamente.
 */
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

    protected function setUp()
    {
        $this->assembler = new Assembler();
    }

    /** @dataProvider providerTestFiles */
    public function testAssemblerWithProgram($programPath)
    {
        $compareFile = $this->getHackCompareFile($programPath);

        $expected = $compareFile->read();

        $asmFile = $this->getAsmProgramFile($programPath);

        $result = $this->assembler->assemble($asmFile);

        $this->assertEquals($expected, $result);
    }

    public function providerTestFiles()
    {
        $testFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;

        $filesDir = [
            'add/Add',
            'max/Max',
            'max/MaxL',
            'pong/Pong',
            'pong/PongL',
            'pong/PongL',
            'rect/Rect',
            'rect/RectL',
        ];

        $filesPath = [];

        foreach ($filesDir as $file) {
            $filesPath[] = [$testFilePath . str_replace('/', DIRECTORY_SEPARATOR, $file)];
        }

        return $filesPath;
    }

    private function getAsmProgramFile($programPath): IOStream
    {
        $file = new IOStream($programPath . '.asm', 'r');

        return $file;
    }

    private function getHackCompareFile($programPath): IOStream
    {
        $file = new IOStream($programPath . '.hack', 'r');

        return $file;
    }
}