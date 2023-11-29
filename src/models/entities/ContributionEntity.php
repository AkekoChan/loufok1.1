<?php
    namespace App\Entities;

    use App\Models;

    class ContributionEntity {
        public int $id_contribution;
        public int|null $id_user;
        public string $text;
        public string $created_at;
        public int $submission_order;
        public int $id_cadavre_exquis;
        public int $id_admin;

        public string $cadavre_title;

        public function __construct()
        {
            $this->cadavre_title = Models\CadavreExquisModel::instance()->getCadavreTitle($this->id_cadavre_exquis);
        }

        public function getUser () : UserEntity {
            if($this->id_user === null) {
                return Models\UsersModel::instance()->getUserById($this->id_admin, true);
            }
            return Models\UsersModel::instance()->getUserById($this->id_user, false);
        }
    }
?>