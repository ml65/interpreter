<?php

namespace App\Functions;

use App\Interfaces\FunctionInterface;

class JsonFunction implements FunctionInterface {
    public function execute(array $params, array $context = []): mixed {
        if (empty($params)) {
            return '{}';
        }

        $value = $params[0];
        if (!is_array($value)) {
            $value = ['message' => $value];
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    
    public function getName(): string {
        return 'json';
    }
} 