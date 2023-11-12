<?php
    namespace App\Controllers;

    use App\Models;
    use App\Service\Interfaces\Controller;
    use App\Service\Routing\Response;
    use App\Templates\Views;

    class Index extends Controller {
        public function index () : Response {
            return $this->response
                ->dump(Models\ContributionModel::instance()->findAll())
                ->template(Views\Index::class)
                ->status(202);
        }
    }
?>