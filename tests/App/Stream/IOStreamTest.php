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
        vfsStream::newFile($this->testFileName, 0000)
            ->at($this->root);

        $this->expectException(FileNotFoundException::class);

        @new IOStream($this->filePath, 'r');
    }

    public function testGetStreamReturnsResource()
    {
        vfsStream::newFile($this->testFileName, 0700)
            ->at($this->root);

        $expected = 'stream';

        $stream = new IOStream($this->filePath, 'r');

        $result = get_resource_type($stream->getStream());

        $this->assertEquals($expected, $result);
    }

    public function testIsEOFReturnsFalseOnNotEndOfFile()
    {
        $fileContents = 'test' . PHP_EOL . 'file' . PHP_EOL . 'contents';

        vfsStream::newFile($this->testFileName, 0700)
            ->withContent($fileContents)
            ->at($this->root);

        $stream = new IOStream($this->filePath, 'r');

        $result = $stream->isEOF();

        $this->assertEquals(false, $result);
    }

    public function testIsEOFReturnsTrueOnEndOfFile()
    {
        $fileContents = 'test' . PHP_EOL . 'file' . PHP_EOL . 'contents';

        vfsStream::newFile($this->testFileName, 0700)
            ->withContent($fileContents)
            ->at($this->root);

        $stream = new IOStream($this->filePath, 'r');

        $stream->readLine();
        $stream->readLine();
        $stream->readLine();
        $result = $stream->isEOF();

        $this->assertEquals(true, $result);
    }

    public function testReadReadsEntireFile()
    {
        $fileContents = 'test' . PHP_EOL . 'file' . PHP_EOL . 'contents';

        vfsStream::newFile($this->testFileName, 0700)
            ->withContent($fileContents)
            ->at($this->root);

        $expected = $fileContents;

        $stream = new IOStream($this->filePath, 'r');

        $result = $stream->read();

        $this->assertEquals($expected, $result);
    }

    public function testReadReadsLineFromFile()
    {
        $fileContentsArray = [
            'test',
            'file',
            'contents'
        ];

        $fileContents = implode(PHP_EOL, $fileContentsArray);

        vfsStream::newFile($this->testFileName, 0700)
            ->withContent($fileContents)
            ->at($this->root);

        $stream = new IOStream($this->filePath, 'r');

        $i = 0;
        while (!$stream->isEOF()) {
            $expected = $fileContentsArray[$i];
            $result = $stream->readLine();

            $this->assertEquals($expected, $result);

            $i++;
        }
    }
//
//    public function testCloseClosesResource();
}