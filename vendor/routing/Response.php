<?php
    namespace App\Service\Routing;

    use App\Service\Managers\Error;

    class Response {
        private int $status = 200;
        private array $headers = [];

        private mixed $content;
        private array $dump = [];

        private array $responses = [ 100=>'Continue', 101=>'Switching Protocols', 200=>'OK', 201=>'Created', 202=>'Accepted', 203=>'Non-Authoritative Information', 204=>'No Content', 205=>'Reset Content', 206=>'Partial Content', 300=>'Multiple Choices', 301=>'Moved Permanently', 302=>'Moved Temporarily', 303=>'See Other', 304=>'Not Modified', 305=>'Use Proxy', 400=>'Bad Request', 401=>'Unauthorized', 402=>'Payment Required', 403=>'Forbidden', 404=>'Not Found', 405=>'Method Not Allowed', 406=>'Not Acceptable', 407=>'Proxy Authentication Required', 408=>'Request Time-out', 409=>'Conflict', 410=>'Gone', 411=>'Length Required', 412=>'Precondition Failed', 413=>'Request Entity Too Large', 414=>'Request-URI Too Large', 415=>'Unsupported Media Type', 418 => "I'm a teapot", 429=>'Too Many Requests', 500=>'Internal Server Error', 501=>'Not Implemented', 502=>'Bad Gateway', 503=>'Service Unavailable', 504=>'Gateway Time-out', 505=>'HTTP Version not supported' ];

        public function __destruct()
        {
            if(!isset($this->content)) return;
            if(headers_sent()) die(); // CASE OF FATALS OMG
            ob_end_clean();
            header("HTTP/1.0 {$this->status} {$this->responses[$this->status]}", true, $this->status);
            header("Content-Type: text/plain", true); // DEFAULT
            header('Strict-Transport-Security:max-age=31536000;includeSubDomains', true);
            header('X-XSS-Protection:1;mode=block', true);
            
            foreach($this->headers as $header => $value) {
                header(ucwords($header, "-").":$value", true);
            }
            
            if(ENV->MODE === "DEV") {
                foreach($this->dump as $dump) {
                    var_dump($dump);
                    echo PHP_EOL;
                }
            }

            header("X-Powered-By:Mosaic " . ENV->VERSION, true);
            return die($this->render());
        }

        public function dump (mixed $content) : Response {
            $this->dump[] = $content;
            return $this;
        }

        private function render () : mixed {
            if(!isset($this->content)) return null;
            if(is_callable($this->content) && !is_string($this->content)) return ($this->content)();
            if(is_array($this->content)) return $this->renderJson();
            if(is_object($this->content)) return $this->renderClass();
            if(is_int($this->content) || is_float($this->content)) return strval($this->content);
            return $this->content;
        }

        private function renderJson () : string {
            header("Content-Type: application/json", true);
            $this->content["status"] = $this->status;
            return json_encode($this->content);
        }

        private function renderClass () : mixed {
            if(method_exists($this->content, 'render')) {
                // It's a template ! Yipi !
                header("Content-Type: text/html; charset=UTF-8", true);
                return $this->content->render();
            } else if(method_exists($this->content, '__toString')) {
                return $this->content->__toString();
            } else {
                return "(class name) " . $this->content::class;
            }
        }

        // TODO FUNCTION LIMIT:limit = 429 Too Many Request when needed

        public function content (mixed $content) : Response {
            $this->content = $content ?? ""; // ? Can't be null because this would kill the response
            return $this;
        }

        public function cache (string $value) : Response {
            $defaults = ["-public" => "public,max-age=3600,must-revalidate", "-disable" => "no-store,no-cache,must-revalidate"];
            $this->headers["cache-control"] = $defaults[$value] ?? $value;
            return $this;
        }

        public function type (string $value) : Response {
            $defaults = ["-json" => "application/json", "-html" => "text/html; charset=UTF-8",
                "-raw" => "text/plain", "-png" => "image/png", "-svg" => "image/svg+xml"];
            $this->headers["content-type"] = $defaults[$value] ?? $value;
            return $this;
        }

        public function template (string $template, array $variables = []) : Response {
            if(class_exists($template)) {
                $this->content = new $template($variables);
            } else {
                $this->content = "Template $template does not exist.";
            }
            // Warning
            return $this;
        }

        public function file (string $path, bool $download = false, string $filename = null) : Response {
            if($download) {
                $this->header("content-disposition", "attachment; filename=" . $filename ?? "download_" . uniqid() . "_" . time());
                $this->cache("must-revalidate");
            }
            if(!str_starts_with($path, ROOT)) $path = ROOT . $path;
            $this->content = fn() => readfile($path);
            return $this;
        }

        public function redirect (string $url, bool $permanent = false) : Response {
            $this->status = $permanent ? 301 : 302;
            $this->headers["location"] = (ENV->PATH_EXCLUDE ? "/" . ENV->PATH_EXCLUDE . "/" : null) . trim($url, "/");
            $this->headers["cache-control"] = "no-store,no-cache,must-revalidate";
            $this->content = "Redirected to <a href='$url'>$url</a>";
            return $this;
        }

        public function throw (int $status = 404) : Response {
            return Error::throw($this, $status);
        }

        public function status (int $status = 200) : Response {
            $this->status = isset($this->responses[$status]) ? $status : http_response_code();
            return $this;
        }

        public function header (string $header, string $value) : Response {
            if(!isset($this->headers[$header])) $this->headers[$header] = $value;
            return $this;
        }

        public function getDetails () : array {
            $response = $this->responses[$this->status];
            $headers = $this->headers;

            return [
                "status" => $this->status,
                "response" => $response,
                "headers" => $headers
            ];
        }
    }
?>