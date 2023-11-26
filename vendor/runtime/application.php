<?php
    // *   __    __  ______  ______  ______  __  ______   
    // *  /\ '-./  \/\  __ \/\  ___\/\  __ \/\ \/\  ___\  
    // *  \ \ \-./\ \ \ \/\ \ \___  \ \ \_\ \ \ \ \ \____ 
    // *   \ \_\ \ \_\ \_____\/\_____\ \_\ \_\ \_\ \_____\
    // *    \/_/  \/_/\/_____/\/_____/\/_/\/_/\/_/\/_____/
    // *    

    // * Mosaic PHP Framework | Created with ♡ (at least)
    // * https://maksance.dev/mosaic

    use App\Service\Managers\Resources;
    use App\Service\Routing\Response;
    use App\Service\Routing\Request;
    use App\Service\Routing\Route;
    
    use App\Service\Debug\ServiceFatal;

    class ApplicationRunTime {
        private array $environment;
        public function __construct (string $root)
        {
            define("ROOT", $root);
            define("ENV", $this);

            set_exception_handler(fn($f) => new ServiceFatal($f));
            set_error_handler(fn(...$f) => new ServiceFatal($f), E_ALL);
            error_reporting(E_ALL);
            
            require_once realpath(ROOT . "/vendor/runtime/autoload.php");

            $this->environment = array_merge(
                parse_ini_file(realpath(ROOT . "/src/config/.env"), false, INI_SCANNER_TYPED),
                parse_ini_file(realpath(ROOT . "/vendor/runtime/service.ini"), false, INI_SCANNER_TYPED)
            );

            if(!isset($this->environment["MODE"]) || $this->environment["MODE"] === "AUTO") {
                $this->environment["MODE"] = (Request::instance()->server === "localhost" ||
                Request::instance()->server === "127.0.0.1" ? "DEV" : "PROD");
            }

            ob_start();
            Resources::init();
            require_once realpath(ROOT . "/src/config/routes.php");
            Route::resolve();
        }

        public function __get(string $name) : mixed
        {
            return !isset($this->environment[$name]) || empty($this->environment[$name]) ?  
            $this->environment[ENV->MODE."_$name"] ?? $this->environment["DEFAULT_".ENV->MODE."_$name"] ?? $this->environment["DEFAULT_$name"] ??
                    null : $this->environment[$name];
        }
    }
?>