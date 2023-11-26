<?php
    namespace App\Service\Routing;

    class Request {
        public array $get;
        public array $post;
        public array $cookie;
        public array $headers;
        public array $auth;

        public array|null $session;

        public string|null $apikey;
        public string|null $authorization;

        public string|null $origin;

        public string $server;
        public string $method;
        public string|null $accept;
        public string|null $content;
        public string $port;
        public string $protocol;
        public string $remote;
        public string $uri;
        public string $trace;
        public string $useragent;
        public string $langage;
        public array $route;

        public int $time;
        public float $ftime;

        public bool $fetch;

        private static Request $instance;

        public static function instance () : Request {
            if(!isset(self::$instance)) self::$instance = new self();
            return self::$instance;
        }

        protected function __construct()
        {
            $this->get = $_GET;
            $this->post = array_merge($_POST, json_decode(trim(file_get_contents("php://input") ?? ""), true) ?? []);
            $this->method = $_SERVER["REQUEST_METHOD"];
            $this->cookie = $_COOKIE;
            $this->session = $_SESSION ?? null;
            $this->uri = urldecode("/" . trim(preg_replace('/'.ENV->PATH_EXCLUDE.'/i', '', strtok($_SERVER["REQUEST_URI"], "?"), 1), "/"));
            $this->auth = [ "USER" => $_SERVER["PHP_AUTH_USER"] ?? null, "PW" => $_SERVER["PHP_AUTH_PW"] ?? null ];
            $this->accept = $_SERVER["HTTP_ACCEPT"] ?? null;
            $this->content = $_SERVER["HTTP_CONTENT_TYPE"] ?? null;
            $this->port = $_SERVER["SERVER_PORT"];
            $this->server = $_SERVER["SERVER_NAME"];
            $this->origin = $_SERVER["HTTP_ORIGIN"] ?? $_SERVER['HTTP_REFERER'] ?? null;
            $this->protocol = $_SERVER["SERVER_PROTOCOL"];
            $this->remote = $_SERVER["REMOTE_ADDR"];
            $this->headers = getallheaders();
            $this->apikey = $this->headers['X-API-Key'] ?? null;
            $this->authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
            $this->fetch = isset($_SERVER["HTTP_SEC_FETCH_MODE"]) && $_SERVER["HTTP_SEC_FETCH_MODE"] == "cors";
            $this->time = $_SERVER["REQUEST_TIME"];
            $this->ftime = $_SERVER["REQUEST_TIME_FLOAT"];
            $this->useragent = $_SERVER["HTTP_USER_AGENT"];
            $this->langage = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
            $this->trace = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        }
    }
?>