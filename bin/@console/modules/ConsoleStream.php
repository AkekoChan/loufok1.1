<?php
    namespace App\Console\Modules;

    class ConsoleStream {
        private $dialogResourceIn;
        private $dialogResourceOut;

        private bool $valid = true;

        public function __construct()
        {
            if(STDIN !== null && STDOUT !== null) {
                $this->dialogResourceIn = STDIN;
                $this->dialogResourceOut = STDOUT;
            } else {
                $this->valid = false;
            }
        }

        public function isValid () : bool {
            return $this->valid;
        }

        public function write (string|int $content) : bool {
            return fwrite($this->dialogResourceOut, $content);
        }

        public function read () : string|int {
            $content = fgets($this->dialogResourceIn);
            return is_numeric($content) ? intval($content) : $content;
        }
    }
?>