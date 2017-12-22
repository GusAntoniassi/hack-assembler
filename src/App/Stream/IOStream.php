<?php
/**
 * Wrapper para um resource
 *
 * Utiliza as funções padrões do PHP para ler, escrever e tratar arquivos.
 */
namespace App\Stream;

use App\Exception;

class IOStream
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     * @param string $mode
     * @return resource
     * @throws Exception\FileNotFoundException
     */
    public function __construct($path, $mode)
    {
        $this->path = $path;
        $this->stream = fopen($path, $mode);
        if ($this->stream === FALSE) {
            throw new Exception\FileNotFoundException($path);
        }

        return $this->stream;
    }

    /**
     * @return resource
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @return boolean
     */
    public function isEOF()
    {
        return feof($this->stream);
    }

    /**
     * @return string
     */
    public function read()
    {
        return fread($this->stream, filesize($this->path));
    }

    /**
     * @return string
     */
    public function readLine()
    {
        return fgets($this->stream);
    }

    /**
     * @param string $string
     * @return int
     */
    public function write($string)
    {
        return fwrite($this->stream, $string);
    }

    /**
     * @return bool
     */
    public function close()
    {
        return fclose($this->stream);
    }
}