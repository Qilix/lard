<?php

namespace Lard\Database;

class DB
{
    const CONFIG = [
        'login' => 'root',
        'pass' => 'root',
        'host' => 'localhost',
        'db' => 'lard_test'
    ];

    public static function getConnect()
    {
        try {
            return new \PDO('mysql:host=' . self::CONFIG['host'] . ';dbname=' . self::CONFIG['db'], self::CONFIG['login'], self::CONFIG['pass']);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

}
