<?php
    namespace App\Controllers;

    use App\Helpers\Auth;
    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;

    use App\Models;
    use App\Entities;

    class Controls extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();

            if($user === false || !$user->is_admin) return $this->response->content([
                "error" => "Auth failed, no user logon"
            ])->status(401);

            $cadavres = Models\CadavreExquisModel::instance()->findAll();
            $periodes = array_column($cadavres, "periode");
            $titles = array_column($cadavres, "title");

            return $this->response->content([
                "periodes" => $periodes,
                "titles" => $titles
            ])->status(200);
        }
    }
?>