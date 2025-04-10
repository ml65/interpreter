<?php

namespace App\Functions;

use App\Interfaces\FunctionInterface;

class ConcatFunction implements FunctionInterface {
    public function execute(array $params, array $context = []): mixed {
        if (empty($params)) {
            return '';
        }

        $result = '';
        foreach ($params as $param) {
            if (is_array($param)) {
                $result .= $param[0] ?? '';
            } else {
                $result .= (string)$param;
            }
        }
        return $result;
    }
    
    public function getName(): string {
        return 'concat';
    }
} 