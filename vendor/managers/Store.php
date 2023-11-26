<?php
    namespace App\Service\Managers;

    class Store {
        public static function setCookie (string $name, mixed $value, int $expire = 0) : bool {
            return setcookie($name, $value, $expire, "/" . ENV->PATH_EXCLUDE);
        }

        public static function removeCookie (string $name) : bool {
            if (isset($_COOKIE[$name])) {
                unset($_COOKIE[$name]); 
                setcookie($name, "", time() - 3600, "/" . ENV->PATH_EXCLUDE); 
                return true;
            } else {
                return false;
            }
        }

        public static function getCookie (string $name) : mixed {
            return $_COOKIE[$name] ?? null;
        }

        public static function destroySession () : bool {
            $_SESSION = array();
            $r = session_destroy();
            $r |= session_unset();
            return $r;
        }

        public static function setSession (string $name, mixed $value) : bool {
            if(session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION[$name] = $value;
            return true;
        }

        public static function getSession (string $name) : mixed {
            if(session_status() === PHP_SESSION_ACTIVE) {
               return $_SESSION[$name] ?? null;
            } else {
                return null;
            }
        }
    }
?>