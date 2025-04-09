<?php

namespace App\Lexer;

// Лексер
class Lexer {
    private int $position = 0;
    private string $input;

    public function __construct(string $input) {
        $this->input = trim($input);
    }

    public function tokenize(): array {
        $tokens = [];
        while ($this->position < strlen($this->input)) {
            $char = $this->input[$this->position];
            
            if (ctype_space($char)) {
                $this->position++;
                continue;
            }

            switch ($char) {
                case '(':
                case ')':
                case ',':
                    $tokens[] = new Token($char, $char);
                    $this->position++;
                    break;
                case '"':
                    $tokens[] = $this->parseString();
                    break;
                default:
                    $tokens[] = $this->parseIdentifierOrNumber();
                    break;
            }
        }
        return $tokens;
    }

    private function parseString(): Token {
        $value = '';
        $this->position++; // пропускаем открывающую кавычку
        
        while ($this->position < strlen($this->input) && $this->input[$this->position] !== '"') {
            $value .= $this->input[$this->position];
            $this->position++;
        }
        
        $this->position++; // пропускаем закрывающую кавычку
        return new Token('STRING', $value);
    }

    private function parseIdentifierOrNumber(): Token {
        $value = '';
        while ($this->position < strlen($this->input) && 
               !in_array($this->input[$this->position], [' ', ',', '(', ')'])) {
            $value .= $this->input[$this->position];
            $this->position++;
        }

        if ($value === 'true' || $value === 'false' || $value === 'null') {
            return new Token('CONSTANT', $value);
        }
        
        if (is_numeric($value)) {
            return new Token('NUMBER', $value);
        }
        
        return new Token('IDENTIFIER', $value);
    }
}
