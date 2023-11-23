<?php
    namespace App\Controllers;

    use App\Helpers\Auth;
    use App\Service\Routing\Response;
    use App\Service\Interfaces\Controller;

    use App\Templates\Views;
    use App\Models;

    class Cadavre extends Controller {
        public function index () : Response {
            $user = Auth::fromCookie();
            if($user === false) return $this->response->redirect("/login");

            $last_cadavre = $user->getLastContributedCadavre();

            if($last_cadavre === null) {
                return $this->response->template(Views\NoLastCadavre::class, [
                    "user" => $user
                ]);
            }

            $contributions = $last_cadavre->contributions;

            $contributors = $last_cadavre->getContributors();

            $user_contribution = $user->getContributionFromCadavre($last_cadavre->id_cadavre_exquis);

            return $this->response->template(Views\Cadavre::class, [
                "user" => $user,
                "cadavre" => $last_cadavre,
                "contributions" => $contributions,
                "contributors" => $contributors,
                "user_contribution" => $user_contribution
            ]);
        }
    }
?>