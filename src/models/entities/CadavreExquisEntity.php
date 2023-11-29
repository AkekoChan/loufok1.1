<?php

namespace App\Entities;

use App\Helpers\Periode;
use App\Models;

class CadavreExquisEntity
{
    // FROM TABLE
    public int $id_cadavre_exquis;
    public string $title;
    public string $date_start;
    public string $date_end;
    public int $max_contributions;
    public int $nb_like;
    public int $id_admin;

    // CUSTOM
    public UserEntity $admin;
    public array $contributions;

    public int $contributions_left;

    public Periode $periode;

    public function __construct()
    {
        $this->admin = Models\UsersModel::instance()->getUserById($this->id_admin, true);
        $this->contributions = Models\ContributionModel::instance()->findBy(["id_cadavre_exquis" => $this->id_cadavre_exquis]);

        $this->periode = new Periode($this->date_start, $this->date_end);
        $this->contributions_left = $this->max_contributions - (count($this->contributions) ?? 0);
    }

    public function isActualCadavre () : bool {
        return $this->periode->isActive() && $this->contributions_left > 0;
    }

    public function isEnded () : bool {
        return date('Y-m-d') > $this->date_end || $this->contributions_left <= 0;
    }

    public function getContributors () : array {
        return Models\CadavreExquisModel::instance()->getAllContributors($this->id_cadavre_exquis);
    }
}
