<?php

namespace App\Interfaces;

// Интерфейс для токенов
interface TokenInterface {
    public function getType(): string;
    public function getValue(): string;
}

