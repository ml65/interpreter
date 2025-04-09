<?php

namespace App\Parser;

use Exception;
use App\Parser\AST\ASTNode;
use App\Parser\AST\FunctionCallNode;
use App\Parser\AST\ConstantNode;

// Парсер
class Parser {
    private array $tokens;
    private int $position = 0;

    public function __construct(array $tokens) {
        $this->tokens = $tokens;
    }

    public function parse(): ASTNode {
        return $this->parseExpression();
    }

    private function parseExpression(): ASTNode {
        if ($this->position >= count($this->tokens)) {
            throw new Exception("Unexpected end of input");
        }

        $token = $this->tokens[$this->position];

        if ($token->getType() === '(') {
            return $this->parseFunctionCall();
        }

        return $this->parseConstant();
    }

    private function parseFunctionCall(): ASTNode {
        $this->position++; // пропускаем '('
        $functionName = $this->tokens[$this->position++]->getValue();
        $parameters = [];

        if ($this->tokens[$this->position]->getType() !== ')') {
            while ($this->position < count($this->tokens) && 
                   $this->tokens[$this->position]->getType() !== ')') {
                if ($this->tokens[$this->position]->getType() === ',') {
                    $this->position++;
                    continue;
                }
                $parameters[] = $this->parseExpression();
            }
        }

        $this->position++; // пропускаем ')'
        return new FunctionCallNode($functionName, $parameters);
    }

    private function parseConstant(): ASTNode {
        $token = $this->tokens[$this->position++];
        $type = $token->getType();
        
        if (in_array($type, ['STRING', 'NUMBER', 'CONSTANT'])) {
            return new ConstantNode($token->getValue());
        }
        
        throw new Exception("Unexpected token: {$token->getValue()}");
    }
}
