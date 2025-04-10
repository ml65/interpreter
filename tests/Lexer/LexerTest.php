<?php

namespace App\Tests\Lexer;

use App\Lexer\Lexer;
use App\Lexer\Token;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public function testTokenizeSimpleExpression(): void
    {
        $lexer = new Lexer('add(1, 2)');
        $tokens = $lexer->tokenize();

        $this->assertCount(6, $tokens);
        $this->assertTokenEquals('IDENTIFIER', 'add', $tokens[0]);
        $this->assertTokenEquals('(', '(', $tokens[1]);
        $this->assertTokenEquals('NUMBER', '1', $tokens[2]);
        $this->assertTokenEquals(',', ',', $tokens[3]);
        $this->assertTokenEquals('NUMBER', '2', $tokens[4]);
        $this->assertTokenEquals(')', ')', $tokens[5]);
    }

    public function testTokenizeString(): void
    {
        $lexer = new Lexer('"hello world"');
        $tokens = $lexer->tokenize();

        $this->assertCount(1, $tokens);
        $this->assertTokenEquals('STRING', 'hello world', $tokens[0]);
    }

    public function testTokenizeConstants(): void
    {
        $lexer = new Lexer('true false null');
        $tokens = $lexer->tokenize();

        $this->assertCount(3, $tokens);
        $this->assertTokenEquals('CONSTANT', 'true', $tokens[0]);
        $this->assertTokenEquals('CONSTANT', 'false', $tokens[1]);
        $this->assertTokenEquals('CONSTANT', 'null', $tokens[2]);
    }

    public function testTokenizeWithWhitespace(): void
    {
        $lexer = new Lexer("  add  ( 1 ,  2 )  ");
        $tokens = $lexer->tokenize();

        $this->assertCount(6, $tokens);
        $this->assertTokenEquals('IDENTIFIER', 'add', $tokens[0]);
        $this->assertTokenEquals('(', '(', $tokens[1]);
        $this->assertTokenEquals('NUMBER', '1', $tokens[2]);
        $this->assertTokenEquals(',', ',', $tokens[3]);
        $this->assertTokenEquals('NUMBER', '2', $tokens[4]);
        $this->assertTokenEquals(')', ')', $tokens[5]);
    }

    private function assertTokenEquals(string $expectedType, string $expectedValue, Token $token): void
    {
        $this->assertEquals($expectedType, $token->getType());
        $this->assertEquals($expectedValue, $token->getValue());
    }
} 