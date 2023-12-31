<?php

namespace App\Controllers;

use App\Service\Interfaces\Controller;
use App\Service\Routing\Response;
use App\Templates\Views;

use App\Helpers\Auth;
use App\Models;
use App\Entities;
use App\Service\Managers\Store;

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

        if($current_cadavre !== null) $user_contribution = $user->getContributionFromCadavre($current_cadavre->id_cadavre_exquis);

        // c'est une requete post omg
        if(!empty($this->request->post)) {
            if ($current_cadavre !== null && $user_contribution === null) {
                // traitement contribution creation (exist, length ok)
                $text = $this->request->post["contribution"] ?? null;

                $textlen = mb_strlen(str_replace(["\n", "\r", "\t"], "", $text ?? ""));
                if($textlen < 50 || $textlen > 280) return $this->response->redirect("/?error=Le texte de contribution doit faire entre 50 et 280 caractères. ({$textlen})");
            
                try {
                    Models\ContributionModel::instance()->create([
                        "id_user" => $user->id, 
                        "text" => $text,
                        "id_cadavre_exquis" => $current_cadavre->id_cadavre_exquis,
                        "id_admin" => $current_cadavre->id_admin
                    ], false);
                } catch (\Throwable $th) {
                    return $this->response->redirect("/?error={$th->getMessage()}");
                }

                $user_cadavre = $user->getLastContributedCadavre();
                if($user_cadavre !== null && $user_cadavre->id_cadavre_exquis === $current_cadavre->id_cadavre_exquis) {
                    return $this->response->redirect("/last/?success");
                }

                return $this->response->redirect("/?success");
            }
            return $this->response->redirect("/"); // L'utilisateur a déjà contribué
        }

        if ($current_cadavre === null || Store::getCookie("locked")) {
            return $this->response->template(Views\Index::class, [
                "user" => $user,
                "cadavre" => null,
                "title" => "Loufok | Aucun Cadavre Exquis en cours"
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
            "contribution" => $user_contribution,
            "error" => $this->request->get["error"] ?? null,
            "success" => isset($this->request->get["success"]) ? "Contribution ajouté !" : null,
            "title" => "Loufok | Contribuez au Cadavre Exquis en cours"
        ]);
    }

    public function admin(Entities\UserEntity $user): Response
    {
        // c'est une requete post omg j'en veux pas
        if(!empty($this->request->post)) {
            $this->response->redirect("/");
        }

        $cadavres = Models\CadavreExquisModel::instance()->getAllCadavresNotFinished();

        return $this->response->template(Views\Admin\Index::class, [
            "user" => $user,
            "cadavres" => $cadavres,
            "error" => $this->request->get["error"] ?? null,
            "success" => isset($this->request->get["success"]) ? "Cadavre Exquis (".$this->request->get["success"].") crée !" : null,
            "title" => "Loufok | Cadavres exquis"
        ]);
    }   
}
