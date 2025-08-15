<?php
namespace App\Core;

trait Loggable
{
    public function log(string $level, string $message, array $context = []): void
    {
        Logger::log($level, $message, $context);
    }

    public function emergency(string $message, array $context = []): void { Logger::emergency($message, $context); }
    public function alert(string $message, array $context = []): void { Logger::alert($message, $context); }
    public function critical(string $message, array $context = []): void { Logger::critical($message, $context); }
    public function error(string $message, array $context = []): void { Logger::error($message, $context); }
    public function warning(string $message, array $context = []): void { Logger::warning($message, $context); }
    public function notice(string $message, array $context = []): void { Logger::notice($message, $context); }
    public function info(string $message, array $context = []): void { Logger::info($message, $context); }
    public function debug(string $message, array $context = []): void { Logger::debug($message, $context); }
}
