<?php

namespace App\Parser\AST;

use App\Interfaces\ASTNodeInterface;

class FunctionCallNode extends AstNode {
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
            'array' => count($evaluatedParams) === 2 
                ? [$evaluatedParams[0] => $evaluatedParams[1]]
                : $evaluatedParams,
            'concat' => implode('', $evaluatedParams),
            'map' => array_map(
                fn($item) => ['message' => $this->parameters[1]->evaluate(array_merge($args, [$item]))[0]],
                $evaluatedParams[0]
            ),
            'json' => json_encode($evaluatedParams[0][0], JSON_UNESCAPED_UNICODE),
            'getArg' => $args[(int)$evaluatedParams[0]] ?? null,
            default => throw new \Exception("Unknown function: {$this->functionName}")
        };
    }
} 