<?php
    namespace App\Console\Modules;

    class ConsoleStyling {
        private array $colors = ["black" => 30,"red" => 31,"green" => 32,"yellow" => 33,"blue" => 34,"pink" => 35,"lightblue" => 36,"white" => 37];
        private array $styles = ["default" => 0,"bold" => 1,"faint" => 2,"italic" => 3,"underline" => 4,"blink" => 5, "invert" => 6];

        private array $ASCIIS;

        public function __construct()
        {
            $this->ASCIIS = [
                "mosaic" => file_get_contents(dirname(__DIR__, 1) . "/import/ascii/mosaic"),
                "php" => file_get_contents(dirname(__DIR__, 1) . "/import/ascii/php"),
                "cat" => file_get_contents(dirname(__DIR__, 1) . "/import/ascii/cat")
            ];
        }

        private function useColor (string $colorName) : string {
            $color = $this->colors[$colorName];
            return "\e[$color"."m";
        }

        private function useBackgroundColor (string $colorName) : string {
            $color = ($this->colors[$colorName] ?? $this->colors["white"]) + 10;
            return "\e[$color"."m";
        }

        private function useStyle (string $styleName) : string {
            $style = $this->styles[$styleName] ?? $this->styles["default"];
            return "\e[$style"."m";
        }

        public function parseString (string $input) : string {
            $output = preg_replace_callback('/\[('.implode("|", array_keys($this->colors)).')\]/', fn($color) => $this->useColor($color[1]), $input);
            $output = preg_replace_callback('/\[bg\-('.implode("|", array_keys($this->colors)).')\]/', fn($color) => $this->useBackgroundColor($color[1]), $output);
            $output = preg_replace_callback('/\[('.implode("|", array_keys($this->styles)).')\]/', fn($style) => $this->useStyle($style[1]), $output);
            $output = str_replace("[/]", "\e[0m", $output);
            return $output;
        }

        public function parseBlockString (string $input, string $background) : string {
            $color = $this->useBackgroundColor($background);
            $content = $this->parseString($input);
            $reset = "\033[0m";

            $offset = mb_strlen($content) + mb_strlen($color) + 11;
            $frame = mb_strlen(preg_replace('/\e\[[0-9;]*[mK]/', '', $content)) + 12 - mb_strlen($color);

            $output = $color . str_repeat(' ', $frame) . $reset . PHP_EOL; 
            $output .= $color . str_pad($content . $reset . $color, $offset, ' ', STR_PAD_BOTH) . $reset . PHP_EOL;
            $output .= $color . str_repeat(' ', $frame) . $reset . PHP_EOL;
            return $output;
        }

        public function getASCII (string $ASCIIName) : string {
            return $this->ASCIIS[$ASCIIName] ?? "";
        }
    }
?>