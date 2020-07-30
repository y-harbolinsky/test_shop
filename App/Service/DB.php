<?php declare(strict_types = 1);

namespace App\Service;

/**
 * Class DB
 *
 * @package App\Service
 */
class DB
{
    /**
     * @var null|\PDO
     */
    private static $dbh = null;

    /**
     * @return null|\PDO
     */
    public static function getDB(): ?\PDO
    {
        try
        {
            if (is_null(self::$dbh))
            {
                self::$dbh = new \PDO(
                    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
                    DB_USER,
                    DB_PASS,
                    [
                        \PDO::ATTR_PERSISTENT => true,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    ]
                );
            }
        }
        catch (\PDOException $exception)
        {
            //TODO: handle error
        }

        return self::$dbh;
    }
}
