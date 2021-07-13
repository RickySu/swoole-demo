<?php

namespace App;

use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool as SwoolePDOPool;

class PDOPool
{
    const SERVER_HOST = 'localhost';
    const SERVER_PORT = 3306;
    const SERVER_DB_NAME = 'test';
    const SERVER_USERNAME = 'test';
    const SERVER_PASSWORD = '12345';
    const SERVER_CHARSET = 'utf8mb4';

    protected static ?SwoolePDOPool $pool = null;

    public static function getPool(): SwoolePDOPool
    {
        if(!self::$pool){
            self::$pool = new SwoolePDOPool(self::getConfig());
        }
        return self::$pool;
    }

    private static function getConfig(): PDOConfig
    {
        return (new PDOConfig())
            ->withHost(self::SERVER_HOST)
            ->withPort(self::SERVER_PORT)
            ->withDbname(self::SERVER_DB_NAME)
            ->withUsername(self::SERVER_USERNAME)
            ->withPassword(self::SERVER_PASSWORD)
            ->withCharset(self::SERVER_CHARSET)
            ;
    }
}