<?php

namespace PRGANYAR\MVC\TEST\Config;

use PDO;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(string $env = 'test'): PDO
    {
        if(self::$pdo == null)
        {
            require_once __DIR__ . "/../../config/Database.php";
            $config = getConfig();
            self::$pdo = new \PDO(
                $config['database'][$env]['url'],
                $config['database'][$env]['username'],
                $config['database'][$env]['password'],
            );
        }

        return self::$pdo;
    }

    public static function start()
    {
        self::$pdo->beginTransaction();
    }

    public static function commit()
    {
        self::$pdo->commit();
    }

    public static function rollback()
    {
        self::$pdo->rollBack();
    }
}
