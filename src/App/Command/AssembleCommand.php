<?php
namespace App\Command;

use App\Assembler\Assembler;
use App\Stream\IOStream;
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

    protected function execute(InputInterface $input, OutputInterface $consoleStream)
    {
        $filenameArg = $input->getArgument('filename');

        $inputStream = $this->getInputFileStream($filenameArg);
        $outputStream = $this->getOutputFileStream($filenameArg);

        $assembledOutput = $this->assembler->assemble($inputStream);

        $outputStream->write($assembledOutput);

        $inputStream->close();
        $outputStream->close();

        $consoleStream->writeln('Assembly ended successfully');
    }

    private function getFilePathDetails($path) {
        return [
            'path' => dirname(realpath(APP_ROOT . DIRECTORY_SEPARATOR . $path)),
            'filename' => basename($path)
        ];
    }

    private function getInputFileStream($basePath) {
        $pathDetails = $this->getFilePathDetails($basePath);
        $path = $pathDetails['path'] ?? '';
        $filename = $pathDetails['filename'] ?? '';

        $file = new IOStream($path . DIRECTORY_SEPARATOR . $filename, 'r');

        return $file;
    }

    private function getOutputFileStream($basePath, $outputExtension = '.hack') {
        $pathDetails = $this->getFilePathDetails($basePath);
        $path = $pathDetails['path'] ?? '';
        $inputFilename = $pathDetails['filename'] ?? '';

        $outFilename = substr($inputFilename, 0, strpos($inputFilename, '.')) . $outputExtension;
        $outStream = new IOStream($path . DIRECTORY_SEPARATOR . $outFilename, 'w');

        return $outStream;
    }
}