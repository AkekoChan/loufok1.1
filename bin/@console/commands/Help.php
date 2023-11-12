<?php
    namespace App\Console\Commands;

    use App\Console\ConsoleInterface;

    class Help {
        public function __construct() {
            $console = ConsoleInterface::getInstance();
            $classes = scandir(__DIR__);
            array_shift($classes);
            array_shift($classes);
            
            $console->write('[white]Avaible commands:[/]'.PHP_EOL);
                
            foreach(array_map(function($class) { 
                return strtolower(strtok($class, ".")); 
            }, $classes) as $command) {
                $class = "Service\\Console\\Commands\\$command";
                $subcommands = array_diff(get_class_methods($class), [ "__construct" ]);
                $subcommands = empty($subcommands) ? "" : "[white]: [blue][" . implode("|", $subcommands) . "]";
                $console->write("[white]> [pink]$command $subcommands");
            }
        }
    }
?>