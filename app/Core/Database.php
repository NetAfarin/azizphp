<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static PDO $pdo;

    public static function initialize(array $config)
    {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
            self::$pdo = new PDO($dsn, $config['username'], $config['password']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            define('BASE_PATH', dirname(__DIR__, 2));
            $errorMessage = "متأسفانه امکان اتصال به پایگاه داده وجود ندارد. لطفاً بعداً تلاش کنید.";
            include __DIR__ . '/../Views/errors/database.php';
            exit;
        }

    }

    public static function pdo(): PDO
    {
        return self::$pdo;
    }
}


