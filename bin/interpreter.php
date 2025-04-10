#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Interpreter\Interpreter;

// Функция для вывода ошибок
function printError(string $message): void {
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}


if (count($argv) == 1) {

    echo "\nUssage: \n";
    echo "\n    interpreter <program_file>: [<param>[ <param>...]] \n";
    echo "\n    cat <program_file> | interpreter [<param>[ <param>...]] \n\n";
    exit;
}

if ($argc > 1 && file_exists($argv[1])) {
    $program = file_get_contents($argv[1]);
    if (!$program) {
        printError("File read ERROR: {$argv[1]}");
    }
    array_shift($argv);
} else {
    $program = stream_get_contents(STDIN);
//    $program = fgets(STDIN);
    if (!$program) {
        printError("STDIN read ERROR");
    }
}
array_shift($argv);
$args = $argv;

try {
    $interpreter = new Interpreter();
    $result = $interpreter->interpret($program ?? '', $args);
    echo $result,"\n";
} catch (Throwable $e) {
    printError("Execution ERROR: " . $e->getMessage());
}
