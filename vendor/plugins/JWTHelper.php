<?php
    namespace App\Service\Plugins;

    class JWTHelper {   
        private string $hashkey;

        public function __construct(string $hashkey)
        {
            $this->hashkey = $hashkey;
        }

        public function encode_token (array $data) : string {
            $data["timestamp"] = time();
            $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
            $payload = json_encode($data);
        
            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);
        
            $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->hashkey, true);
            $base64UrlSignature = $this->base64UrlEncode($signature);
        
            $accessToken = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
        
            return $accessToken;
        }

        public function decode_token (string $token) : array|bool {
            list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);
        
            $header = $this->base64UrlDecode($base64UrlHeader);
            $payload = $this->base64UrlDecode($base64UrlPayload);
        
            $decodedSignature = $this->base64UrlDecode($base64UrlSignature);
            $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $this->hashkey, true);
        
            if ($decodedSignature !== $signature) {
                return false;
            }
        
            $data = json_decode($payload, true);
            return $data;
        }

        private function base64UrlEncode($data) : string {
            $base64 = base64_encode($data);
            $base64Url = strtr($base64, '+/', '-_');
            return rtrim($base64Url, '=');
        }
        
        private function base64UrlDecode($data) : string {
            $base64Url = strtr($data, '-_', '+/');
            $base64 = base64_decode($base64Url);
            return $base64;
        }
    }
?>