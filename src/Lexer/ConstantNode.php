<?php

namespace App\Lexer;

use App\Interfaces\ASTNodeInterface;

// Узел константы
class ConstantNode implements ASTNodeInterface {
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
