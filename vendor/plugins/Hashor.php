<?php
    namespace App\Service\Plugins;

    class Hashor {
        public static function hash (string $value, string $hashor)
        {
            return substr_replace(hash_hmac('sha256', $value, $hashor, false), 
                    substr(hash_hmac('sha256', $value, $hashor, false), 0, 22), 0, 22);
        }
    }
?>