<?php

namespace App\Tests\Lexer;

use App\Lexer\Token;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function testTokenCreation(): void
    {
        $token = new Token('IDENTIFIER', 'test');
        
        $this->assertEquals('IDENTIFIER', $token->getType());
        $this->assertEquals('test', $token->getValue());
    }

    public function testTokenToString(): void
    {
        $token = new Token('NUMBER', '42');
        
        $this->assertEquals('Token(NUMBER, 42)', (string)$token);
    }

    public function testTokenEquality(): void
    {
        $token1 = new Token('STRING', 'hello');
        $token2 = new Token('STRING', 'hello');
        $token3 = new Token('STRING', 'world');
        
        $this->assertTrue($token1->equals($token2));
        $this->assertFalse($token1->equals($token3));
    }
} 