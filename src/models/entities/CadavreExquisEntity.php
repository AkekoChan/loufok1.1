<?php
    namespace App\Models\Entities;

    class CadavreExquisEntity {
        public int $id_game;
        public string $title;
        public string $date_start;
        public string $date_end;
        public int $nb_contribution;
        public int $nb_like;
        public int $id_admin;
    }
?>