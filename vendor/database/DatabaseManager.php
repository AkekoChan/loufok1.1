<?php
    namespace App\Service\Database;

    class DatabaseManager {
        private static \PDO $resource;
        protected function __construct()
        {
            $host = ENV->DB_HOST;
            $user = ENV->DB_USER;
            $pass = ENV->DB_PASS;
            $database = ENV->DB_NAME;
            self::$resource = new \PDO("mysql:host=$host;dbname=$database", $user, $pass, 
                [
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]);
        }

        public static function getInstance () : \PDO {
            if(!isset(self::$resource)) new self();
            return self::$resource;
        }

        public static function query(string $sql, array $attributs = null)
        {
            if ($attributs !== null) {
                $sth = self::getInstance()->prepare($sql);
                $sth->execute($attributs);
                return $sth;
            } else {
                return self::getInstance()->query($sql);
            }
        }
    }
?>