<?php
    namespace App\Service\Interfaces;

    use App\Service\Routing\Response;
    use App\Service\Routing\Request;
    use App\Service\Routing\Route;

    class Controller {
        protected Request $request;
        protected Response $response;
        
        public function __construct(Request $request, Response $response)
        {
            $this->request = $request;
            $this->response = $response;
        }

        final public static function bind (string|array $route, array $methods = ["GET"], string $module = "index") {
            $routes = is_array($route) ? $route : [$route];
            return Route::queue($routes, $methods, [static::class, $module]);
        }
    }
?>