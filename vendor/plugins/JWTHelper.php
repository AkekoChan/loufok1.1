<?php
    namespace App\Service\Plugins;

    class JWTHelper {   
        public static function encode_token (array $data, string $hashkey) : string {
            $data["timestamp"] = time();
            $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
            $payload = json_encode($data);
        
            $base64UrlHeader = self::base64UrlEncode($header);
            $base64UrlPayload = self::base64UrlEncode($payload);
        
            $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $hashkey, true);
            $base64UrlSignature = self::base64UrlEncode($signature);
        
            $accessToken = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
        
            return $accessToken;
        }

        public static function decode_token (string $token, string $hashkey) : array|bool {
            list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);
        
            $header = self::base64UrlDecode($base64UrlHeader);
            $payload = self::base64UrlDecode($base64UrlPayload);
        
            $decodedSignature = self::base64UrlDecode($base64UrlSignature);
            $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $hashkey, true);
        
            if ($decodedSignature !== $signature) {
                return false;
            }
        
            $data = json_decode($payload, true);
            return $data;
        }

        private static function base64UrlEncode($data) : string {
            $base64 = base64_encode($data);
            $base64Url = strtr($base64, '+/', '-_');
            return rtrim($base64Url, '=');
        }
        
        private static function base64UrlDecode($data) : string {
            $base64Url = strtr($data, '-_', '+/');
            $base64 = base64_decode($base64Url);
            return $base64;
        }
    }
?>