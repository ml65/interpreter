<?php
declare(strict_types=1);

use App\Interpreter\Interpreter;

$program = '(json, (map, (array, "message"), (array, (concat, "Hello, ", (getArg, 0)))))';
$interpreter = new Interpreter();
$result = $interpreter->interpret($program, ["World"]);
echo $result; // Вывод: {"message":"Hello, World"}
