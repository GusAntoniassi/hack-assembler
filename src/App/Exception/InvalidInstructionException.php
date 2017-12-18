<?php
namespace App\Exception;

class InvalidInstructionException extends \Exception {
    public function __construct($instruction, $motivo, $code = 0, Exception $previous = null) {
        $message = "Instrução '$instruction' inválida!\nMotivo: $motivo";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}