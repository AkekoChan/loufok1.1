<?php
    namespace App\Controllers;

    use App\Service\Interfaces\Controller;
    use App\Service\Routing\Response;
    use App\Templates\Views;

    use App\Helpers\Auth;

    class Login extends Controller {
        public function template (string $error = null) : Response {
            $user = Auth::fromCookie();
            if($user !== false) $this->response->redirect("/");

            return $this->response
                ->template(Views\Login::class, [ "title" => "Loufok | Connexion", "error" => $error, "request" => $this->request->post ]);
        }

        public function post () : Response {
            $auth = Auth::fromPost($this->request->post);
            if($auth === true) return $this->response->redirect("/");
            return $this->template($auth["error"] ?? "mail");
        }

        public function logout () : Response {
            Auth::removeCookie();
            return $this->response->redirect("/login", true);
        }
    }
?>