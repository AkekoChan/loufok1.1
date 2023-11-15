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


        return $this->response
            ->template(Views\Index::class, ["user" => $user])
            ->status(200);
        // $current_cadavre = Models\CadavreExquisModel::instance()->getCurrentCadavre();

        // return $user->is_admin ? $this->admin($user, $current_cadavre) : $this->user($user, $current_cadavre);
    }

    // public function user(Entities\UserEntity $user, Entities\CadavreExquisEntity|bool $cadavre_exquis): Response
    // {
    //     // GET ALEATOIRE CONTIBUTION
    //     return $this->response->content([
    //         "periode" => $cadavre_exquis->periode->getConvertedPeriode(),
    //         "remaining_days" => $cadavre_exquis->periode->getRemainingDays(),
    //         "cadavre" => $cadavre_exquis
    //     ]);
    // }

    // public function admin(Entities\UserEntity $user, Entities\CadavreExquisEntity|bool $cadavre_exquis): Response
    // {
    //     return $this->response->content("admin");
    // }
}
