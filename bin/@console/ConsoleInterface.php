<?php
    namespace App\Console;
    
    use App\Console\Modules\ConsoleStyling;
    use App\Console\Modules\ConsoleCommand;
    use App\Console\Modules\ConsoleStream;
    
    class ConsoleInterface {
        private ConsoleStyling $consoleStyling;
        private ConsoleCommand $consoleCommand;
        private ConsoleStream $consoleStream;

        private static ConsoleInterface $instance;

        public function __construct()
        {
            $this->consoleStream = new ConsoleStream();

            if(!$this->consoleStream->isValid()) {
                exit(print(PHP_EOL . "\e[31m\e[1mConsole Error: can't access STDIN / STDOUT constant\e[0m" . PHP_EOL));
            }

            self::$instance = $this;

            $this->consoleStyling = new ConsoleStyling();
            $this->consoleCommand = new ConsoleCommand();
        }

        public function write (string $message) : bool {
            $parsedMessage = $this->consoleStyling->parseString($message);
            return $this->consoleStream->write(PHP_EOL . $parsedMessage);
        }

        public function writeInfoBlock (string $message, string $background) : bool {
            $parsedMessage = $this->consoleStyling->parseBlockString($message, $background);
            return $this->consoleStream->write(PHP_EOL . $parsedMessage);
        }
        
        public function get (string $message) : string|int {
            $this->write($message);
            return $this->consoleStream->read();
        }

        public function getMultiple (array $dialog) : array {
            $dialogVars = [];
            foreach($dialog as $var => $message) {
                $this->write($message);
                $dialogVars[$var] = $this->consoleStream->read();
            }
            return $dialogVars;
        }

        public function getASCII (string $ASCIIName) : string {
            return $this->consoleStyling->getASCII($ASCIIName);
        }

        public function __destruct()
        {
            return $this->consoleStream->write("\e[0m" . PHP_EOL . PHP_EOL);
        }

        public static function getInstance () : ConsoleInterface {
            return self::$instance;
        }
    }
?>