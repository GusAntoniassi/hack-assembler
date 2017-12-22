<?php
/**
 * Comando que inicia o processo de assembly
 *
 * Utiliza a classe IOStream para abrir os arquivos de entrada e saída, que já
 * irá tratar as exceções possíveis ao mexer com os arquivos. Passa o arquivo
 * de entrada para o Assembler, e escreve seu retorno (o arquivo traduzido para
 * binário) no arquivo de saída.
 *
 * @example assemble {filename}
 */
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

    /**
     * @param Assembler $assembler
     */
    public function __construct(Assembler $assembler)
    {
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

    /**
     * Faz assembly do comando recebido como argumento no $input
     *
     * @param InputInterface $input
     * @param OutputInterface $consoleStream
     */
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

    /**
     * Retorna a pasta pai e o filename de uma string de caminho
     *
     * @param type $path
     * @return type
     */
    private function getFilePathDetails($path)
    {
        return [
            'path'     => dirname(realpath(APP_ROOT . DIRECTORY_SEPARATOR . $path)),
            'filename' => basename($path)
        ];
    }

    /**
     * Retorna o IOStream para um caminho passado em $basePath
     *
     * @param type $basePath Caminho do arquivo de entrada
     * @return IOStream
     */
    private function getInputFileStream($basePath)
    {
        $pathDetails = $this->getFilePathDetails($basePath);
        $path = $pathDetails['path'] ?? '';
        $filename = $pathDetails['filename'] ?? '';

        $file = new IOStream($path . DIRECTORY_SEPARATOR . $filename, 'r');

        return $file;
    }

    /**
     * Cria um novo IOStream com base em um caminho do arquivo de entrada, com
     * o mesmo diretório e nome de arquivo, porém com a extensão do arquivo
     * alterada para $outputExtension
     *
     * @param type $basePath Caminho do arquivo de entrada
     * @param type $outputExtension (optional) Extensão de saída do arquivo. Padrão: .hack
     * @return IOStream
     */
    private function getOutputFileStream($basePath, $outputExtension = '.hack')
    {
        $pathDetails = $this->getFilePathDetails($basePath);
        $path = $pathDetails['path'] ?? '';
        $inputFilename = $pathDetails['filename'] ?? '';

        $outFilename = substr($inputFilename, 0, strpos($inputFilename, '.')) . $outputExtension;
        $outStream = new IOStream($path . DIRECTORY_SEPARATOR . $outFilename, 'w');

        return $outStream;
    }
}