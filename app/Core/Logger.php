<?php
namespace App\Core;

use PDO;
use DateTime;

class Logger
{
    private static string $logDir = __DIR__ . '/../../logs/';
    private static ?PDO $pdo = null;
    private static bool $logToDatabase = true;

    private static array $config = [
        'separate_levels' => [],
        'combined_file'   => true,
        'max_days'        => 30,
        'admin_email'     => null,
        'email_rate_limit'=> 300
    ];

    private static array $productionLevels = ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'];

    public static function configure(array $settings): void
    {
        self::$config = array_merge(self::$config, $settings);
    }

    public static function configureFromConstant(): void
    {
        if (defined('LOGGER_CONFIG')) {
            self::configure(LOGGER_CONFIG);
        }
    }

    private static function init(): void
    {
        if (!self::$pdo) {
            self::$pdo = Database::pdo();
        }
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }
        self::cleanupOldLogs();
    }

    private static function getLogFile(string $level): ?string
    {
        $date = date('Y-m-d');
        if (in_array(strtoupper($level), self::$config['separate_levels'])) {
            return self::$logDir . strtolower($level) . "-{$date}.log";
        }
        if (self::$config['combined_file']) {
            return self::$logDir . "app-{$date}.log";
        }
        return null;
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        self::init();
        $level = strtoupper($level);
        if (defined('APP_DEBUG') && APP_DEBUG === false) {
            if (!in_array($level, self::$productionLevels)) {
                return;
            }
        }
        $time = (new DateTime())->format('Y-m-d H:i:s');
        $contextJson = $context ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logFile = self::getLogFile($level);
        if ($logFile) {
            $entry = "[{$time}] {$level}: {$message} " . ($contextJson ?: '') . PHP_EOL;
            file_put_contents($logFile, $entry, FILE_APPEND);
        }
        if (self::$logToDatabase && self::$pdo) {
            try {
                $stmt = self::$pdo->prepare("INSERT INTO logs (level, message, context) VALUES (?, ?, ?)");
                $stmt->execute([$level, $message, $contextJson ?: null]);
            } catch (\Exception $e) {
                if ($logFile) {
                    file_put_contents($logFile, "[{$time}] ERROR: Logger DB failed - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                }
            }
        }
        if (defined('APP_DEBUG') && APP_DEBUG === false) {
            if (in_array($level, ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'])) {
                self::sendAdminAlert($level, $message, $contextJson);
            }
        }
    }

    private static function sendAdminAlert(string $level, string $message, string $contextJson = ''): void
    {
        if (empty(self::$config['admin_email'])) {
            return;
        }
        $rateFile = self::$logDir . 'last_email_' . strtolower($level) . '.txt';
        $now = time();
        if (file_exists($rateFile)) {
            $lastSent = (int)file_get_contents($rateFile);
            if (($now - $lastSent) < self::$config['email_rate_limit']) {
                return;
            }
        }
        $subject = "[{$level}] Alert from " . (defined('APP_NAME') ? APP_NAME : 'Application');
        $body = "Time: " . date('Y-m-d H:i:s') . "\n";
        $body .= "Level: {$level}\n";
        $body .= "Message: {$message}\n";
        if ($contextJson) {
            $body .= "Context: {$contextJson}\n";
        }
        @mail(self::$config['admin_email'], $subject, $body);
        file_put_contents($rateFile, $now);
    }

    private static function cleanupOldLogs(): void
    {
        $maxDays = self::$config['max_days'];
        if ($maxDays <= 0) return;
        $files = glob(self::$logDir . '*.log');
        $now = time();
        foreach ($files as $file) {
            if (is_file($file) && ($now - filemtime($file)) > ($maxDays * 86400)) {
                unlink($file);
            }
        }
    }

    public static function emergency(string $message, array $context = []): void { self::log('EMERGENCY', $message, $context); }
    public static function alert(string $message, array $context = []): void { self::log('ALERT', $message, $context); }
    public static function critical(string $message, array $context = []): void { self::log('CRITICAL', $message, $context); }
    public static function error(string $message, array $context = []): void { self::log('ERROR', $message, $context); }
    public static function warning(string $message, array $context = []): void { self::log('WARNING', $message, $context); }
    public static function notice(string $message, array $context = []): void { self::log('NOTICE', $message, $context); }
    public static function info(string $message, array $context = []): void { self::log('INFO', $message, $context); }
    public static function debug(string $message, array $context = []): void { self::log('DEBUG', $message, $context); }
}
