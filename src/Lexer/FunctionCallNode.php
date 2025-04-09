<?php

namespace App\Lexer;

use App\Interfaces\ASTNodeInterface;

// Узел вызова функции
class FunctionCallNode implements ASTNode {

    private array $parameters;

    public function __construct(
        private string $functionName,
        array $parameters = []
    ) {
        $this->parameters = $parameters;
    }

    public function evaluate(array $args = []): mixed {
        $evaluatedParams = array_map(fn($param) => $param->evaluate($args), $this->parameters);
        
        return match ($this->functionName) {
            'array' => count($evaluatedParams) === 2 && is_string($evaluatedParams[0])
                ? [$evaluatedParams[0] => $evaluatedParams[1]]
                : $evaluatedParams,
            'concat' => implode('', $evaluatedParams),
            'map' => array_reduce(
                $evaluatedParams[0],
                fn($carry, $item) => array_merge(
                    $carry,
                    $this->parameters[1]->evaluate(array_merge($args, [$item]))
                ),
                []
            ),
            'json' => json_encode($evaluatedParams[0]),
            'getArg' => $args[(int)$evaluatedParams[0]] ?? null,
            default => throw new Exception("Unknown function: {$this->functionName}")
        };
    }
}
    
    