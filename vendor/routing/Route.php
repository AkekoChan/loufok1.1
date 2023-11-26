<?php
    namespace App\Service\Routing;

    use App\Service\Managers\Error;

    class Route {
        private static array $queue = [];
        
        public static function queue (array $routes, array $methods, array $controller) {
            self::$queue[] = [
                "routes" => $routes,
                "methods" => $methods,
                "controller" => $controller
            ];
        }

        private static array $route_types = [ "float" => '(\d+[.]?\d*)', "string" => '(\S+)', "bool" => '(\d{1})', "int" => '(\d+)' ];

        public static function resolve () {
            $request = Request::instance();

            foreach(self::$queue as $queue) {
                $routes = $queue["routes"];
                $methods = $queue["methods"];
                list($controller, $module) = $queue["controller"];
                
                if(!class_exists($controller) || !method_exists($controller, $module)) continue; // WARNING
                if($methods !== ["*"] && !in_array($request->method, $methods)) continue; // WARNING

                foreach($routes as $route) {
                    preg_match_all('/{\S*:?([^:]+)}/U', $route, $func_args);
                    $psroute = preg_replace_callback('/{(\S*)?:?[^:]+}/U', function($matches) {
                        return isset($matches[1]) ? self::$route_types[$matches[1]] ?? '([^/]+)' : '([^/]+)';
                    }, $route);
                    if (preg_match('#^/?' . $psroute . '/*$#', $request->uri, $arguments)) {
                        array_shift($arguments);
                        $args = array_combine($func_args[1], $arguments);
                        $request->route = [$controller,$module,$route,$methods];
                        return (new $controller($request, new Response))->{$module}(...$args);
                    }
                }
            }
            
            Error::throw(new Response, 404);
        }
    }
?>