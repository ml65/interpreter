<?php

namespace App\Functions;

use App\Interfaces\FunctionInterface;

class GetArgFunction implements FunctionInterface {
    public function execute(array $params, array $context = []): mixed {
        if (empty($params)) {
            throw new \InvalidArgumentException('GetArg function requires an index parameter');
        }

        $index = (int)$params[0];
        if ($index < 0 || $index >= count($context)) {
            throw new \InvalidArgumentException("Invalid argument index: {$index}");
        }

        return $context[$index];
    }
    
    public function getName(): string {
        return 'getArg';
    }
} 