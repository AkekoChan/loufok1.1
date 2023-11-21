<?php
    namespace App\Controllers;

    use App\Helpers\Auth;
    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;
    
    use App\Models\UsersModel;
    
    use App\Templates\Views;

    class Profile extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if ($user === false) return $this->response->redirect("/login");

            $contribution = UsersModel::instance()->getAllContributions($user->id);

            return $this->response->template(Views\Profile::class, [
                "user" => $user,
                "contributions" => $contribution
            ]);
        }
    }
?>