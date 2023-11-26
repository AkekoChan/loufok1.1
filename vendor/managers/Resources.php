<?php
    namespace App\Service\Managers;

    use App\Service\Routing\Request;
    use App\Service\Routing\Response;

    class Resources {
        public static function init () : Response|null {
            $request = Request::instance();
            $path = ROOT . "/public" . $request->uri;
            if(!file_exists($path) || !is_file($path) || in_array($request->uri, ["/index.php", "/.htaccess"])) return null;
            $extension = pathinfo($path, PATHINFO_EXTENSION); 
            $mime = ["css" => "text/css", "js" => "text/javascript"][$extension] ?? mime_content_type($path);
            $response = new Response();
            return $response->file($path)
                ->header("X-Content-Type-Options", "nosniff")
                ->type($mime)
                ->cache("-public")
                ->status(200);
        }
    }
?>