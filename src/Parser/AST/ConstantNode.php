<?php

namespace App\Parser\AST;

use App\Interfaces\ASTNodeInterface;

class ConstantNode extends AstNode {

    public function __construct(private string $value) {}

    public function evaluate(array $args = []): mixed {
        return match ($this->value) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $this->value
        };
    }
}