<?php
/**
 * Exceção lançada quando uma instrução possui sintaxe inválida
 */
namespace App\Exception;

class EmptyInstructionException extends \Exception
{

    public function __construct($code = 0, Exception $previous = null)
    {
        $message = "Instrução não pode ser vazia";
        parent::__construct($message, $code, $previous);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}