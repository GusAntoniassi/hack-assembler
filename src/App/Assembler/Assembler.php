<?php
namespace App\Assembler;

class Assembler {
    public function assemble($file) {
        while (!feof($file)) {
            $line = fgets($file);

            echo $line;
        }

        fclose($file);
    }
}