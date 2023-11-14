<?php
    namespace App\Models\Entities;

    class ContributionEntity {
        public int $id_contribution;
        public int|null $id_user;
        public string $text;
        public string $created_at;
        public int $submission_order;
        public int $id_cadavre_exquis;
        public int $id_admin;
    }
?>