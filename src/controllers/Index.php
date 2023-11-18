<?php

namespace App\Controllers;

use App\Service\Interfaces\Controller;
use App\Service\Routing\Response;
use App\Templates\Views;

use App\Helpers\Auth;
use App\Models\Entities;
use App\Models;

// Ce controlleur pointe sur /
class Index extends Controller
{
    public function index(): Response
    {
        $user = Auth::fromCookie();
        if ($user === false) return $this->response->redirect("/login");

        // $current_cadavre = Models\CadavreExquisModel::instance()->getCurrentCadavre();

        // return $user->is_admin ? $this->admin($user, $current_cadavre) : $this->user($user, $current_cadavre);
    }

    public function user(Entities\UserEntity $user, Entities\CadavreExquisEntity|bool $cadavre_exquis): Response
    {
        $current_cadavre = Models\CadavreExquisModel::instance()->getCurrentCadavre();

        if ($current_cadavre === null) {
            // TODO: REAL TEMPLATE
            die(var_dump("YA PAS EN COURS OU DEJA COMPLET TODO: REAL TEMPLATE"));
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
            "random_contribution" => $random_contrib
        ]);
    }

    public function admin(Entities\UserEntity $user, Entities\CadavreExquisEntity|bool $cadavre_exquis): Response
    {
        return $this->response->content("admin");
    }
}
