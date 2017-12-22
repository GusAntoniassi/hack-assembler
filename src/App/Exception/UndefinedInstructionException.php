<?php
/**
 * Exception lançada quando uma instrução não é encontrada nas lookup tables
 */
namespace App\Exception;

class UndefinedInstructionException extends \Exception
{

    public function __construct($instruction, $code = 0, Exception $previous = null)
    {
        $message = "Instrução '$instruction' não existente!";
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}