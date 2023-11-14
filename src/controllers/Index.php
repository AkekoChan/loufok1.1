<?php
    namespace App\Controllers;

    use App\Service\Interfaces\Controller;
    use App\Service\Routing\Response;
    use App\Templates\Views;
    
    use App\Helpers\Auth;
    use App\Models\Entities\UserEntity;

    class Index extends Controller {
        public function index () : Response {
            return $this->response
                ->template(Views\Index::class)
                ->status(202);
        }
    }
?>