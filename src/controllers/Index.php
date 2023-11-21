<?php

namespace App\Controllers;

use App\Service\Interfaces\Controller;
use App\Service\Routing\Response;
use App\Templates\Views;

use App\Helpers\Auth;
use App\Models\Entities;
use App\Models;
use App\Models\CadavreExquisModel;

// Ce controlleur pointe sur /
class Index extends Controller
{
    public function index(): Response
    {
        $user = Auth::fromCookie();
        if ($user === false) return $this->response->redirect("/login");

        return $user->is_admin ? $this->admin($user) : $this->user($user);
    }

    public function user(Entities\UserEntity $user): Response
    {
        $current_cadavre = Models\CadavreExquisModel::instance()->getCurrentCadavre();

        $contribution_exist = array_filter(Models\UsersModel::instance()->getAllContributions($user->id),
            fn($contribution) => $contribution->id_cadavre_exquis === $current_cadavre->id_cadavre_exquis)[0] ?? false;

        // c'est une requete post omg
        if(!empty($this->request->post)) {
            if ($current_cadavre !== null && empty($contribution_exist)) {
                // traitement contribution creation
            }

            $this->response->redirect("/");
        }

        if ($current_cadavre === null) {
            return $this->response->template(Views\Index::class, [
                "user" => $user,
                "cadavre" => null
            ]);
        }

        // ContributionEntity
        $random_contrib = Models\RandContributionModel::instance()
            ->getRandomContribution($user->id, $current_cadavre->id_cadavre_exquis);

        // return $this->response->content([
        //     "periode" => $current_cadavre->periode->getConvertedPeriode(),
        //     "remaining_days" => $current_cadavre->periode->getRemainingDays(),
        //     "cadavre" => $current_cadavre,
        //     "random_contribution" => $random_contrib
        // ]);

        return $this->response->template(Views\Index::class, [
            "user" => $user,
            "periode" => $current_cadavre->periode->getConvertedPeriode(),
            "remaining_days" => $current_cadavre->periode->getRemainingDays(),
            "cadavre" => $current_cadavre,
            "random_contribution" => $random_contrib,
            "contribution" => $contribution_exist
        ]);
    }

    public function admin(Entities\UserEntity $user): Response
    {
        $cadavres = CadavreExquisModel::instance()->getAllCadavre();
        return $this->response->template(Views\Admin\Index::class, [
            "user" => $user,
            "cadavres" => $cadavres
        ]);
    }   
}
