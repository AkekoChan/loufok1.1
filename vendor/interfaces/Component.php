<?php
    namespace App\Service\Interfaces;

    class Component extends Template {
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
    }
?>