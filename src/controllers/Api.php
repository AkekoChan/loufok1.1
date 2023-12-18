<?php

namespace App\Controllers;

use App\Models;

use App\Service\Routing\Response;
use App\Service\Interfaces\Controller;

class Api extends Controller
{
    public function cadavres (): Response
    {
        $cadavres = Models\CadavreExquisModel::instance()->getAllCadavresFinished();

        $data = [];

        foreach ($cadavres as $cadavre) {
            $contributeurs = array_column($cadavre->getContributors(), "nom");
            sort($contributeurs, SORT_STRING);

            $data['cadavres'][] = [
                "id_cadavre" => $cadavre->id_cadavre_exquis,
                'titre' => $cadavre->title,
                'nb_likes' => $cadavre->nb_like,
                "auteur" => $cadavre->admin->mail,
                "contributeurs" => $contributeurs,
                "premiere_contribution" => $cadavre->contributions[0]->text
            ];
        }

        $this->response->header("Access-Control-Allow-Origin", "*")
        ->header("Access-Control-Allow-Methods", "GET, POST")
        ->header("Access-Control-Allow-Headers", "Content-Type, Accept, x-requested-with");

        return $this->response->content($data);
    }

    public function cadavre (int $id) : Response
    {
        $cadavre = Models\CadavreExquisModel::instance()->find($id);

        $this->response->header("Access-Control-Allow-Origin", "*")
        ->header("Access-Control-Allow-Methods", "GET, POST")
        ->header("Access-Control-Allow-Headers", "Content-Type, Accept, x-requested-with");

        if($cadavre === null || !$cadavre->isEnded()) return $this->response->content([ "error" => "Not Found" ])->status(404);

        $contributeurs = array_column($cadavre->getContributors(), "nom");
        sort($contributeurs, SORT_STRING);

        return $this->response->content([
            'titre' => $cadavre->title,
            "total_contributions" => count($cadavre->contributions),
            "nb_jours" => $cadavre->periode->getDiff(),
            "nb_likes" => $cadavre->nb_like,
            "auteur" => $cadavre->admin->mail,
            // "contributions_max" => $cadavre->max_contributions,
            "contributions" => array_column($cadavre->contributions, "text"),
            "contributeurs" => $contributeurs,
        ]);
    }

    public function like () : Response
    {
        // TODO: localstorage or cookie store likes
        $id = $this->request->post['id'] ?? null;

        $this->response->header("Access-Control-Allow-Origin", "*")
        ->header("Access-Control-Allow-Methods", "GET, POST")
        ->header("Access-Control-Allow-Headers", "Content-Type, Accept, x-requested-with");

        if($id === null) return $this->response->content([])->status(400);
        
        $cadavreModel = Models\CadavreExquisModel::instance();
        $cadavre = $cadavreModel->find($id);

        if($cadavre === null || !$cadavre->isEnded()) return $this->response->content([])->status(404);

        $success = $cadavreModel->update($id, [
            "nb_like" => $cadavre->nb_like + 1
        ]) ? 200 : 400;

        return $this->response->content([])->status($success);
    }
}
