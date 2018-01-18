<?php

namespace App\Test\Command;

use App\Assembler\Assembler;
use App\Command\AssembleCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class AssembleCommandTest extends TestCase {
    protected function setUp() {
        define('APP_ROOT', __DIR__ . '../../../');
        if (file_exists(APP_ROOT . DIRECTORY_SEPARATOR . 'commandTest.hack')) {
            unlink(APP_ROOT . DIRECTORY_SEPARATOR . 'commandTest.hack');
        }
    }

    public function testCommandExecute() {
        $assembler = new Assembler();
        $command = new AssembleCommand($assembler);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'filename' => 'commandTest.txt'
        ]);

        $this->assertFileExists(APP_ROOT . DIRECTORY_SEPARATOR . 'commandTest.hack');
    }
}