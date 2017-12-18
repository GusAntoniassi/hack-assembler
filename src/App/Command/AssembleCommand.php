<?php
namespace App\Command;

use App\Exception;
use App\Assembler\Assembler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssembleCommand extends Command
{
    private $assembler;

    public function __construct(Assembler $assembler) {
        $this->assembler = $assembler;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('assemble')
            ->setDescription('Compila um arquivo .asm em um arquivo binário .hack')
            ->addArgument(
                'filename', InputArgument::REQUIRED, 'Nome do arquivo a ser compilado'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $path = realpath(APP_ROOT . DIRECTORY_SEPARATOR . $filename);

        @$file = fopen($path, 'r');
        if ($file === FALSE) {
            throw new Exception\FileNotFoundException($filename);
        }

        $this->assembler->assemble($file);

        die();

        $instruction = \App\Instruction\Instruction::getInstruction($filename);

        $output->writeln($instruction->toString());
    }
}