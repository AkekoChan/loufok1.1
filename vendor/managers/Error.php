<?php
    namespace App\Service\Managers;

    use App\Service\Routing\Request;
    use App\Service\Routing\Response;

    class Error {
        public static function throw (Response $response, int $status = 404) : Response {
            $response->status($status)
            ->header("x-robots-tag", "noindex")
            ->cache("-disable");
            
            $responseDetails = $response->getDetails();

            if(str_contains(Request::instance()->accept, "text/html")) {
                $classname = $responseDetails["status"];
                if(class_exists("App\\Templates\\Errors\\$classname\\Index")) {
                    return $response->template("App\\Templates\\Errors\\$classname\\Index", [
                        "code" => $responseDetails["status"],
                        "message" => $responseDetails["response"]
                    ]);
                } else if (class_exists("App\\Templates\\Errors\\Index")) {
                    return $response->template("App\\Templates\\Errors\\Index", [
                        "code" => $responseDetails["status"],
                        "message" => $responseDetails["response"]
                    ]);
                }
                return $response->content("error {$responseDetails['status']} ({$responseDetails['response']})");
            }
            else if(str_contains(Request::instance()->accept, "application/json")) {
                return $response->content([
                    "error" => $responseDetails["response"]
                ]);
            }
            else {
                // file
                return $response->content(null);
            }
        }
    }
?>