<?php
    namespace App\Service\Plugins;

    class Hashor {
        public static function hash (string $value, string $hashor)
        {
            return hash_hmac('sha256', $value, $hashor, false);
        }
    }
?>