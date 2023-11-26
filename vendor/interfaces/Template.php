<?php
    namespace App\Service\Interfaces;
    
    class Template {
        private array $variables;

        public function __construct(array $variables)
        {
            $this->variables = $variables;
        }

        public function __get(string $name) : mixed
        {
            if(isset($this->variables[$name])) {
                return $this->variables[$name];
            } 
            else {
                return null;
            }
        }

        // ? Was working on a function to auto echo content
        // protected function __call(string $name, mixed $values) {
        //     $value = $values[0] ?? null;
        //     if(isset($this->variables[$name]) && $value === null) echo $this->variables[$name];
        //     else if(isset($value) && is_array($this->variables[$name]) && isset($this->variables[$name][$value])) echo $this->variables[$name][$value];
        //     else if(isset($value) && is_object($this->variables[$name]) && property_exists($this->variables[$name], $value)) echo $this->variables[$name]->{$value};
        //     else if(isset($value) && is_object($this->variables[$name]) && method_exists($this->variables[$name], $value)) echo $this->variables[$name]->{$value}();
        // }

        /**
         * @return string echo:return direct path to public resource (with path exclude)
         */
        protected function public (...$paths) : string {
            foreach($paths as $path) {
                if(!file_exists(ROOT . "/public" . $path)) continue;
                $path = "/" . (ENV->PATH_EXCLUDE ? ENV->PATH_EXCLUDE . "/" : null) . trim($path, "/");
                break;
            }
            echo($path);
            return $path;
        }

        /**
         * @return string echo:return direct url with path exclude
         */
        protected function url (string $url) : string {
            $url = "/" . (ENV->PATH_EXCLUDE ? ENV->PATH_EXCLUDE . "/" : null) . trim($url, "/");
            echo($url);
            return $url;
        }

        protected function component (string $component, array $variables = []) {
            if(class_exists($component) && method_exists($component, 'render')) {
                return (new $component(array_merge($this->variables, $variables)))->render();
            } else {
                // TODO WARN
            }
        }
    }
?>