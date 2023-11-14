<?php
    namespace App\Controllers;

    use App\Models;
    use App\Service\Interfaces\Controller;
    use App\Service\Routing\Response;
    use App\Templates\Views;

    use App\Helpers\Auth;

    class Index extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if($user === false) $this->response->redirect("/login");

            return $this->response
                ->template(Views\Index::class)
                ->status(202);
        }
    }
?>