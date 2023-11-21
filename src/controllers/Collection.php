<?php
    namespace App\Controllers;

    use App\Helpers\Auth;
    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;

    use App\Templates\Views;
    use App\Models;

    class Collection extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if($user === false) return $this->response->redirect("/login");

            $user_cadavres = Models\UsersModel::instance()->getAllCadavres($user->id);

            return $this->response->template(Views\Collection::class, [
                "user" => $user,
                "user_cadavres" => $user_cadavres
            ]);
        }

        public function cadavre (int $id) : Response {
            $user = Auth::fromCookie();
            if($user === false) return $this->response->redirect("/login");

            $cadavre = Models\CadavreExquisModel::instance()->find($id);

            if($cadavre === null) {
                $this->response->throw(404);
            }

            $contributions = Models\CadavreExquisModel::instance()->getAllContributions($id);
            $contributors = Models\CadavreExquisModel::instance()->getAllContributors($id);
            $user_contribution = Models\UsersModel::instance()->getContributionFromCadavre($user->id, $cadavre->id_cadavre_exquis);

            return $this->response->template(Views\Cadavre::class, [
                "user" => $user,
                "cadavre" => $cadavre,
                "contributions" => $contributions,
                "contributors" => $contributors,
                "user_contrib" => $user_contribution
            ]);
        }
    }
?>