<?php
    namespace App\Controllers;

    use App\Service\Interfaces\Controller;
    use App\Service\Routing\Response;
    use App\Templates\Views;

    use App\Helpers\Auth;

    class Login extends Controller {
        /**
         * Cette fonction pointe sur /login en méthode GET
        */
        public function template (string $error = null) : Response {
            // ? On récupere l'utilisateur par le cookie
            $user = Auth::fromCookie();
            // ? Si l'utilisateur est authentifiable on redirige sur la page d'accueil
            if($user !== false) return $this->response->redirect("/");

            return $this->response
                ->template(Views\Login::class, [ "title" => "Loufok | Connexion", "error" => $error, "request" => $this->request->post ]);
        }

        /**
         * Cette fonction pointe sur /login en méthode POST
        */
        public function post () : Response {
            $auth = Auth::fromPost($this->request->post);
            if($auth === true) return $this->response->redirect("/");
            return $this->template($auth["error"] ?? "mail");
        }

        /**
         * Cette fonction pointe sur /logout
        */
        public function logout () : Response {
            Auth::removeCookie();
            return $this->response->redirect("/login", true);
        }
    }
?>