<?php

namespace App\Functions;

use App\Interfaces\FunctionInterface;

class ArrayFunction implements FunctionInterface {
    public function execute(array $params, array $context = []): mixed {
        if (empty($params)) {
            return [];
        }

        if (count($params) === 1) {
            return $params;
        }

        if (count($params) === 2) {
            if (is_string($params[0])) {
                return [$params[0] => $params[1]];
            }
            return $params;
        }

        return $params;
    }
    
    public function getName(): string {
        return 'array';
    }
} 