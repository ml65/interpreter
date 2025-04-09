<?php

namespace App\Interfaces;   

// Интерфейс узла AST
interface ASTNodeInterface {
    public function evaluate(array $args = []): mixed;
}
