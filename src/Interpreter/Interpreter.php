<?php

namespace App\Interpreter;

use App\Lexer\Lexer;
use App\Parser\Parser;

// Интерпретатор
class Interpreter {
    public function interpret(string $program, array $externalArgs = []): mixed {
        $lexer = new Lexer($program);
        $tokens = $lexer->tokenize();
        $parser = new Parser($tokens);
        $ast = $parser->parse();
        return $ast->evaluate($externalArgs);
    }
}
