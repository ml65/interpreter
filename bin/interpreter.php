#!/usr/bin/env php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Interpreter\Interpreter;

// Константы для ограничений
const MAX_FILE_SIZE = 1024 * 1024; // 1MB
const MAX_ARGS = 10;

// Функция для вывода ошибок
function printError(string $message): void {
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

// Функция для безопасного чтения файла
function safeReadFile(string $filePath): string {
    // Нормализация пути
    $realPath = realpath($filePath);
    if ($realPath === false) {
        printError("Недопустимый путь к файлу");
    }

    // Проверка, что файл находится в допустимой директории
    if (!str_starts_with($realPath, realpath(__DIR__ . '/..'))) {
        printError("Доступ к файлу за пределами рабочей директории запрещен");
    }

    // Проверка прав доступа
    if (!is_readable($realPath)) {
        printError("Нет прав на чтение файла");
    }

    // Проверка размера файла
    $fileSize = filesize($realPath);
    if ($fileSize === false || $fileSize > MAX_FILE_SIZE) {
        printError("Превышен максимальный размер файла");
    }

    // Проверка типа файла
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo === false) {
        printError("Не удалось определить тип файла");
    }
    
    $mimeType = finfo_file($finfo, $realPath);
    finfo_close($finfo);

    if (!in_array($mimeType, ['text/plain', 'text/x-php', 'application/x-php'])) {
        printError("Неподдерживаемый тип файла");
    }

    // Чтение файла
    $content = file_get_contents($realPath);
    if ($content === false) {
        printError("Ошибка чтения файла");
    }

    return $content;
}

// Функция для безопасного чтения stdin
function safeReadStdin(): string {
    stream_set_blocking(STDIN, false);
    $content = '';
    $bytesRead = 0;

    while (!feof(STDIN)) {
        $chunk = fread(STDIN, 8192);
        if ($chunk === false) {
            printError("Ошибка чтения stdin");
        }
        $bytesRead += strlen($chunk);
        if ($bytesRead > MAX_FILE_SIZE) {
            printError("Превышен максимальный размер входных данных");
        }
        $content .= $chunk;
    }

    if (empty(trim($content))) {
        printError("Пустой ввод");
    }

    return $content;
}

// Проверка количества аргументов
if (count($argv) > MAX_ARGS + 1) { // +1 для имени скрипта
    printError("Превышено максимальное количество аргументов");
}

try {
    // Получаем программу либо из файла, либо из stdin
    if ($argc > 1 && file_exists($argv[1])) {
        $program = safeReadFile($argv[1]);
    } else {
        $program = safeReadStdin();
    }

    // Фильтрация аргументов
    $args = array_map(function($arg) {
        // Удаляем непечатаемые символы
        return preg_replace('/[[:cntrl:]]/', '', $arg);
    }, array_slice($argv, 2));

    // Выполнение интерпретатора
    $interpreter = new Interpreter();
    $result = $interpreter->interpret($program, $args);
    
    // Проверка результата перед выводом
    if (!is_string($result)) {
        $result = json_encode($result, JSON_THROW_ON_ERROR);
    }
    
    echo $result;

} catch (Throwable $e) {
    // Логируем полную ошибку (если есть система логирования)
    // error_log($e->getMessage() . "\n" . $e->getTraceAsString());
    
    // Пользователю выводим только безопасное сообщение
    printError("Ошибка выполнения программы");
}
