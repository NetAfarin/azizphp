<?php
namespace App\Core;

use PDO;

class Database
{
    private static PDO $pdo;

    public static function initialize(array $config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
        self::$pdo = new PDO($dsn, $config['username'], $config['password']);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function pdo(): PDO
    {
        return self::$pdo;
    }
}


