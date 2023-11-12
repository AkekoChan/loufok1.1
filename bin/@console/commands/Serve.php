<?php
    namespace App\Console\Commands;

    use App\Console\ConsoleInterface;

    class Serve {
        public function __construct(int $port = null) {
            $console = ConsoleInterface::getInstance();
            $port = ($port ?? ENV["PORT"] ?? ENV["DEFAULT_PORT"] ?? 8000);
            $console->write("[bold][white]" . $console->getASCII("mosaic") . "[/]\n");
            $console->writeInfoBlock("=> Project served on [bold][underline]http://localhost:$port"."[/]", 'blue');
            $console->write("[white] > You can press Ctrl + C to terminate the process[/]\n\n");
            $command = "php -q -S localhost:$port public/index.php";
            return exec($command);
        }
    }
?>