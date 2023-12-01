<?php
    namespace App\Service\Plugins;

    class JsonHelper {
        public array $data;
        private string $file;

        public function __construct(string $filename)
        {
            $this->file = $filename;

            $path = realpath(ROOT . $this->file);

            if($path) {
                $this->data = json_decode(file_get_contents(realpath(ROOT . $this->file)), true);
            } else {
                file_put_contents(ROOT . $this->file, json_encode([], JSON_PRETTY_PRINT));
                $this->data = [];
            }
        }

        public function __destruct()
        {
            file_put_contents(realpath(ROOT . $this->file), json_encode($this->data, JSON_PRETTY_PRINT));
        }
    }
?>