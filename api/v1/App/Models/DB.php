<?php

namespace App\Models;

use PDO;

class DB
{
    private static PDO $con;

    private function __construct(){}

    public static function obterConexao() : PDO
    {
        if (!isset(self::$con)) {
            self::$con = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            self::$con->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }
        return self::$con;
    }
}