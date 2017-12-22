<?php
/**
 * Modela uma C-Instruction, que é utilizada para fazer cálculos na ULA e manipular
 * o Program Counter para realizar instruções JUMP.
 *
 * As instruções Hack seguem o padrão dest = comp ; jump. A string recebida no
 * construtor é quebrada nessas três partes e seus valores são comparados à
 * InstructionTable para pegar seu número binário equivalente. A representação
 * binária das C-Instructions é a seguinte: 111 (prefixo para todas as instruções)
 * + comp (7 bits) + dest (3 bits) + jump (3 bits)
 */
namespace App\Instruction;

use App\LookupTable\InstructionTable;

class CInstruction implements InstructionInterface
{
    /**
     * @var string
     */
    private $instruction;

    /**
     * @var InstructionTable
     */
    private $lookupTable;

    /**
     * @var string
     */
    private $dest = '';

    /**
     * @var string
     */
    private $comp = '';

    /**
     * @var string
     */
    private $jump = '';

    /**
     * Separa a instrução em suas três partes: dest, comp e jump
     *
     * Utiliza o caractere '=' para separar o dest do comp, e o caractere ';'
     * para separar o jump do comp. No caso de algum desses valores não estar
     * presente, é utilizado uma string vazia em seu lugar. Apenas o comp é
     * obrigatório em todas as instruções, e não pode estar vazio.
     *
     * @param string $instruction
     */
    public function __construct($instruction)
    {
        $this->instruction = $instruction;
        $this->lookupTable = new InstructionTable();

        $comp = $instruction;

        $posEquals = strpos($comp, '=');
        if ($posEquals !== FALSE) {
            $this->dest = substr($comp, 0, $posEquals);
            $comp = substr($comp, $posEquals + 1);
        }

        $posSemicolon = strpos($comp, ';');
        if ($posSemicolon !== FALSE) {
            $this->jump = substr($comp, $posSemicolon + 1);
            $comp = substr($comp, 0, $posSemicolon);
        }

        $this->comp = $comp;
    }

    /**
     * Converte a instrução em um número binário de 16 bits
     *
     * Utiliza a InstructionTable para pegar o valor binário equivalente àquela
     * instrução.
     *
     * @return string
     */
    public function getBinaryCode()
    {
        $inst = '111';
        $inst .= $this->lookupTable->lookup('comp', $this->comp);
        $inst .= $this->lookupTable->lookup('dest', $this->dest);
        $inst .= $this->lookupTable->lookup('jump', $this->jump);

        return $inst;
    }
}