<?php
    spl_autoload_register(function($class) {
        $parts = explode("\\", $class);
        $file = array_pop($parts);
        
        if(isset($parts[0]) && $parts[0] == "App") array_shift($parts);

        $dist = array_shift($parts);

        $destinations = [
            "Controllers" => "/src/controllers",
            "Models" => "/src/models",
            "Entities" => "/src/models/entities",
            "Helpers" => "/src/helpers",
            "Templates" => "/templates",
            "Components" => "/templates/components",
            "Service" => "/vendor",
            "Plugins" => "/vendor/plugins",
            "Console" => "/bin/@console"
        ];

        $destination = $destinations[$dist] ?? null;

        if($destination === null) return false;

        $folders = empty($parts) ? "" : strtolower(implode("/", $parts)) . "/";

        $paths = [
            realpath(ROOT . $destination . "/" . $folders . $file . ".php"),
            realpath(ROOT . $destination . "/" . $folders . strtolower($file) . ".php"),
            realpath(ROOT . $destination . "/" . $folders . ucfirst($file) . ".php"),
            realpath(ROOT . $destination . "/" . $folders . ucwords($file) . ".php"),
            realpath(ROOT . $destination . "/" . $folders . strtolower($file[0]) . substr($file, 1) . ".php"),
        ];

        foreach($paths as $path) {
            if($path && file_exists($path)) {
                return require_once $path;
            }
        }

        return false;
    });
?>