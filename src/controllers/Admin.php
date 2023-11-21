<?php
    namespace App\Controllers;

    use App\Helpers\Auth;
    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;

    use App\Templates\Views;

    class Admin extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if($user === false) return $this->response->redirect("/login");
            if($user->is_admin === false) return $this->response->throw(404);

            if(!empty($this->request->post)) {
                $this->response->redirect("/loufok");
            }

            return $this->response->template(Views\Admin\Create::class, [
                "user" => $user
            ]);
        }
    }
?>