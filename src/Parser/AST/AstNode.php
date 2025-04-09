<?php

namespace App\Parser\AST;

use App\Interfaces\ASTNodeInterface;


abstract class AstNode implements ASTNodeInterface {
    abstract public function evaluate(array $args = []): mixed;
}   