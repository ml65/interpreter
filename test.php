<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Interpreter\Interpreter;
use App\Lexer\Lexer;
use App\Parser\Parser;

$program = '(json, (map, (array, "message"), (array, (concat, "Hello, ", (getArg, 0)))))';
$interpreter = new Interpreter();
$result = $interpreter->interpret($program, ["World"]);
echo $result; // Вывод: {"message":"Hello, World"}



