<?php
    namespace App\Entities;

    use App\Models;
    
    class UserEntity {
        // ONLY USER
        public string|null $nom;
        public string|null $sexe;
        public string|null $bdate;

        // ALL
        public string $password;
        
        // DYNAMIC
        public bool $is_admin;
        public string $mail;
        public int $id;

        public int|null $id_user;
        public int|null $id_admin;

        public function __construct()
        {
            if(!isset($this->id)) {
                $this->id = $this->id_user ?? $this->id_admin;
            }
        }

        public function getContributions () : array|null {
            if($this->is_admin) return Models\ContributionModel::instance()->findBy([
                "id_admin" => $this->id,
                "submission_order" => 1
            ]);

            return Models\ContributionModel::instance()->findBy([
                "id_user" => $this->id
            ]);
        }

        public function getContributionFromCadavre (int $cadavre_id) : ContributionEntity|null {
            if($this->is_admin) return Models\ContributionModel::instance()->findBy([
                "id_admin" => $this->id,
                "submission_order" => 1,
                "id_cadavre_exquis" => $cadavre_id
            ])[0] ?? null;

            return Models\ContributionModel::instance()->findBy([
                "id_user" => $this->id,
                "id_cadavre_exquis" => $cadavre_id
            ])[0] ?? null;
        }

        public function getLastContributedCadavre () : CadavreExquisEntity|null {
            return Models\CadavreExquisModel::instance()->getUserLastCadavre($this->id, $this->is_admin);
        }
    }
?>