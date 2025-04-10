<?php

namespace App\Interfaces;

use App\Lexer\Token;

// Интерфейс для токенов
interface TokenInterface {
    public function getType(): string;
    public function getValue(): string;
    public function __toString(): string;
    public function equals(Token $other): bool;
}

