<?php
namespace App\Test\Assembler;

use App\Assembler\Assembler;
use App\Stream\IOStream;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class AssemblerTest extends TestCase
{
    /**
     * @var Assembler
     */
    private $assembler;
    /**
     * @var vfsStreamDirectory
     */
    private $root;
    private $testFileName;
    private $filePath;

    protected function setUp()
    {
        $this->root = vfsStream::setup();
        $this->testFileName = 'test';
        $this->filePath = $this->root->url() . '/' . $this->testFileName;
        $this->assembler = new Assembler();
    }

    public function testLoadFileIgnoresWhiteLinesAndEmptyComments() {
        $fileContents = '// Comentários serão ignorados' . PHP_EOL
            . '//' . PHP_EOL . PHP_EOL;

        vfsStream::newFile($this->testFileName, 0700)
            ->withContent($fileContents)
            ->at($this->root);

        $expected = '';

        $file = new IOStream($this->filePath, 'r');

        $result = $this->assembler->assemble($file);

        $this->assertEquals($expected, $result);
    }

    /* O resto dos testes necessários são realizados no AssemblerIntegrationTest */
}