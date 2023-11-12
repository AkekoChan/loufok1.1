<?php
    namespace App\Service\Interfaces;

    class Component extends Template {
        private array $variables;

        public function __construct(array $variables)
        {
            $this->variables = $variables;
        }
    }
?>