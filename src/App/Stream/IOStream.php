<?php
namespace App\Stream;

use App\Exception;

class IOStream
{
    private $stream;
    private $path;

    public function __construct($path, $mode)
    {
        $this->path = $path;
        $this->stream = fopen($path, $mode);
        if ($this->stream === FALSE) {
            throw new Exception\FileNotFoundException($path);
        }

        return $this->stream;
    }

    public function getStream()
    {
        return $this->stream;
    }

    public function isEOF()
    {
        return feof($this->stream);
    }

    public function read()
    {
        return fread($this->stream, filesize($this->path));
    }

    public function readLine()
    {
        return fgets($this->stream);
    }

    public function write($string)
    {
        return fwrite($this->stream, $string);
    }

    public function close()
    {
        return fclose($this->stream);
    }
}