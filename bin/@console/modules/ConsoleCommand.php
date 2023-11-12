<?php
    namespace App\Console\Modules;

    use App\Console\ConsoleInterface;

    class ConsoleCommand {
        private ConsoleInterface $console;

        private array $commandInstructions;

        private string|null $commandMain;
        private string|null $commandSub;

        private string $commandClass;

        public function __construct()
        {
            $this->console = ConsoleInterface::getInstance();
            $this->commandInstructions = $_SERVER["argv"];

            array_shift($this->commandInstructions);

            [$this->commandMain, $this->commandSub] = explode(":", array_shift($this->commandInstructions) . ":", 3);

            if(empty($this->commandMain)) {
                $this->console->write("[red][bold]An empty command was specified[/]\n");
                $this->console->write("[white]To see all the avaible commands type \n > [blue]php [yellow]bin/console [pink]help[/]");
            } else {
                $this->commandClass = "App\\Console\\Commands\\" . $this->commandMain;
                $this->execute();
            }
        }

        private function execute () {
            if(class_exists($this->commandClass)) {
                if(isset($this->commandSub) && !empty($this->commandSub)) {
                    if(method_exists($this->commandClass, $this->commandSub)) {
                        return (new $this->commandClass)->{$this->commandSub}(...$this->commandInstructions);
                    }
                    else {
                        return $this->commandSubError();
                    }
                } else {
                    if(!method_exists($this->commandClass, '__construct')) {
                        return $this->commandSubError();
                    }
                    return new $this->commandClass(...$this->commandInstructions);
                }
            } else {
                return $this->commandMainError();
            }
        }

        private function commandMainError () {
            $classes = scandir(dirname(__DIR__, 1) . "/commands");
            array_shift($classes);
            array_shift($classes);
            $classes = $this->filterCommands($classes, 'commandMain');

            $this->console->writeInfoBlock('[bold]Command "'.$this->commandMain.'" does not exist[/]', "red");
            if(empty($classes)) return;
            
            $this->console->write('[white]You might want to use:[/]'.PHP_EOL);
                
            foreach(array_map(function($class) { 
                return strtolower(strtok($class, ".")); 
            }, $classes) as $possibleCommand) {
                $class = "App\\Console\\Commands\\$possibleCommand";
                $subcommands = array_diff(get_class_methods($class), [ "__construct" ]);
                $subcommands = empty($subcommands) ? "" : "[white]: [blue][" . implode("|", $subcommands) . "]";
                $this->console->write("[white]> [pink]$possibleCommand $subcommands");
            }
        }

        private function commandSubError () {
            $methods = array_diff(get_class_methods($this->commandClass), [ "__construct" ]);
            $methods = $this->filterCommands($methods, 'commandSub');

            $this->console->writeInfoBlock('[bold]Command "'.$this->commandMain.':[underline]'.$this->commandSub.'[/][bg-red][bold]" does not exist[/]', "red");
            if(empty($methods)) return;

            $this->console->write('[white]You might want to use:[/]'.PHP_EOL);

            $command = $this->commandMain;
            $methods = "[white]: [blue][" . implode("|", $methods) . "]";
            $this->console->write("[white]> [pink]$command $methods");
        }

        private function filterCommands (array $commands, string $type) {
            return array_slice(array_filter($commands, function ($command) use($type) {
                $command = strtolower(strtok($command, "."));
                return str_contains($this->{$type}, $command) ||
                    str_contains($command, $this->{$type}) || 
                    strlen($this->{$type}) === strlen($command) ||
                    strlen($this->{$type}) === strlen($command) - 2 ||
                    strlen($this->{$type}) === strlen($command) + 2;
            }), 0, 6);
        }
    }
?>