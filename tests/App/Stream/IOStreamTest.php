<?php
namespace App\Test\Stream;

use App\Exception\FileNotFoundException;
use App\Stream\IOStream;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class IOStreamTest extends TestCase
{
    private $root;
    private $filePath;
    private $testFileName;

    protected function setUp()
    {
        $this->root = vfsStream::setup();
        $this->testFileName = 'test';
        $this->filePath = $this->root->url() . '/' . $this->testFileName;
    }

    public function testConstructThrowsFileNotFoundExceptionOnInvalidFile()
    {
        $this->createInvalidTestFile();

        $this->expectException(FileNotFoundException::class);

        @new IOStream($this->filePath, 'r');
    }

    public function testGetStreamReturnsResource()
    {
        $this->createTestFile();

        $expected = 'stream';

        $stream = new IOStream($this->filePath, 'r');

        $result = get_resource_type($stream->getStream());

        $this->assertEquals($expected, $result);
    }

    public function testIsEOFReturnsFalseOnNotEndOfFile()
    {
        $this->createTestFile();

        $stream = new IOStream($this->filePath, 'r');

        $result = $stream->isEOF();

        $this->assertEquals(false, $result);
    }

    public function testIsEOFReturnsTrueOnEndOfFile()
    {
        $this->createTestFile();

        $stream = new IOStream($this->filePath, 'r');

        $stream->readLine();
        $stream->readLine();
        $stream->readLine();
        $result = $stream->isEOF();

        $this->assertEquals(true, $result);
    }

    public function testReadReadsEntireFile()
    {
        $fileContents = $this->createTestFile();

        $expected = $fileContents;

        $stream = new IOStream($this->filePath, 'r');

        $result = $stream->read();

        $this->assertEquals($expected, $result);
    }

    public function testReadReadsLineFromFile()
    {
        $fileContents = $this->createTestFile();

        $fileContentsArray = explode(PHP_EOL, $fileContents);

        $stream = new IOStream($this->filePath, 'r');

        $i = 0;
        while (!$stream->isEOF()) {
            $expected = $fileContentsArray[$i];
            $result = str_replace(PHP_EOL, '', $stream->readLine());

            $this->assertEquals($expected, $result);

            $i++;
        }
    }

    public function testWriteWritesToFile() {
        $this->createTestFile();

        $contentToWrite = 'test';

        $stream = new IOStream($this->filePath, 'w');

        $stream->write($contentToWrite);

        $stream->close();

        $result = file_get_contents($this->filePath);

        $this->assertEquals($contentToWrite, $result);
    }

    public function testCloseClosesResource() {
        
    }

    private function createInvalidTestFile() {
        vfsStream::newFile($this->testFileName, 0000)
            ->at($this->root);
    }

    private function createTestFile() {
        $fileContents = 'test' . PHP_EOL . 'file' . PHP_EOL . 'contents';

        vfsStream::newFile($this->testFileName, 0700)
            ->withContent($fileContents)
            ->at($this->root);

        return $fileContents;
    }
}