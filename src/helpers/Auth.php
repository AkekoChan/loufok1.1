<?php
    namespace App\Helpers;

    use App\Service\Managers\Store;
    use App\Service\Plugins\Hashor;
    use App\Service\Plugins\JWTHelper;

    use App\Models;
    use App\Models\Entities\UserEntity;

    class Auth {
        private static $cookie_name = "user_token";

        public static function removeCookie () : bool {
            return Store::removeCookie(self::$cookie_name);
        }

        public static function fromCookie () : UserEntity|bool {
            $cookie = Store::getCookie(self::$cookie_name);
            if($cookie === null) return false;
            $cookie = JWTHelper::decode_token($cookie, ENV->APP_KEY);

            if(!isset($cookie["mail"]) || !isset($cookie["password"])) return false;

            $user = Models\UsersModel::instance()->retrieveUser($cookie["mail"]);

            if($user === false) return false;

            if($cookie["password"] === $user->password) {
                return $user;
            }

            return false;
        }

        public static function fromPost (array $post) : array|bool {
            if(!isset($post["mail"])) return [ "error" => "mail" ];

            $user = Models\UsersModel::instance()->retrieveUser($post["mail"]);

            if($user === false) return [ "error" => "mail" ];

            if(Hashor::hash($post["password"], ENV->APP_KEY) === $user->password) {
                return Store::setCookie(self::$cookie_name, JWTHelper::encode_token([
                    "mail" => $user->mail,
                    "password" => $user->password
                ], ENV->APP_KEY), 0);
            } else {
                return [ "error" => "password" ];
            }
        }
    }
?>