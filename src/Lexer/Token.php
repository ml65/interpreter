<?php

namespace App\Lexer;

use App\Interfaces\TokenInterface;

class Token implements TokenInterface {
    public function __construct(
        private string $type,
        private string $value
    ) {}

    public function getType(): string {
        return $this->type;
    }

    public function getValue(): string {
        return $this->value;
    }
}

