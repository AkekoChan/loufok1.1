<?php
    namespace App\Controllers;

    use App\Service\Interfaces\Controller;
    use App\Service\Routing\Response;
    use App\Templates\Views;
    
    use App\Helpers\Auth;
    use App\Models;

    class Index extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if($user === false) return $this->response->redirect("/login");
            
            $cadavre = Models\CadavreExquisModel::instance()->getCurrentCadavre();
            die(var_dump($cadavre));

            return $this->response
                ->template(Views\Index::class)
                ->status(200);
        }
    }
?>