<?php
    namespace App\Console\Commands;

    use App\Console\ConsoleInterface;

    class Create {
        private function getBase (string $baseName) : string {
            return file_get_contents(realpath(dirname(__DIR__, 1) . "/import/bases/" . $baseName . ".hls"));
        }

        public function template (string $filename = null) : bool {
            $console = ConsoleInterface::getInstance();
            if(is_null($filename)) {
                $console->writeInfoBlock("Please specify a template name", 'red');
                return $console->write("> [pink]create:template [blue]<template-name>[/]");
            }
            $filename = str_replace("\\", "/", $filename);
            $folders = explode("/", $filename);
            $templateName = array_pop($folders);
            if(is_dir(realpath(ROOT . "/templates/" . implode("/", $folders)))) {
                $path = ROOT . "/templates/$filename.php";
                file_put_contents($path, str_replace("%template%", $templateName, $this->getBase("BaseTemplate")));
                return $console->write("> [green]Template [blue]".$templateName."[green] created [pink]($path)");
            } else {
                return $console->write("> [red]" . ROOT . "/templates/" . implode("/", $folders) . " is not a valid folder");
            }
        }

        public function component (string $filename = null) : bool {
            $console = ConsoleInterface::getInstance();
            if(is_null($filename)) {
                $console->writeInfoBlock("Please specify a component name", 'red');
                return $console->write("> [pink]create:component [blue]<component-name>[/]");
            }
            $filename = str_replace("\\", "/", $filename);
            $folders = explode("/", $filename);
            $componentName = array_pop($folders);
            if(is_dir(realpath(ROOT . "/templates/components/" . implode("/", $folders)))) {
                $path = ROOT . "/templates/components/$filename.php";
                file_put_contents($path, str_replace("%component%", $componentName, $this->getBase("BaseComponent")));
                return $console->write("> [green]Component [blue]".$componentName."[green] created [pink]($path)");
            } else {
                return $console->write("> [red]" . ROOT . "/templates/components/" . implode("/", $folders) . " is not a valid folder");
            }
        }

        public function controller (string $controllerName = null) : bool {
            $console = ConsoleInterface::getInstance();
            $controllerName = trim($controllerName);
            while(is_null($controllerName) || empty($controllerName)) {
                $controllerName = trim($console->get("> [blue]Enter a name for your controller: [/]"));
                if($controllerName === 0) exit();
            }
            $path = ROOT . "/src/controllers/$controllerName.php";
            $bool = file_put_contents($path, str_replace("%controller%", $controllerName, $this->getBase('BaseController')));
            if($bool) {
                return $console->write("> [green]Controller [blue]".$controllerName."[green] created [pink]($path)");
            } else {
                return $console->write("> [red]Controller [blue]".$controllerName."[red] can not be created, folder may not exist [pink]($path)");
            }
        }
    }
?>