<?php
namespace App\Exception;

class FileNotFoundException extends \Exception {
    public function __construct($filename, $code = 0, Exception $previous = null) {
        $message = "Arquivo $filename não encontrado!";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}