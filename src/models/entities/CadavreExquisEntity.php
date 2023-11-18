<?php
    namespace App\Models\Entities;

    use App\Helpers\Periode;

    class CadavreExquisEntity {
        public int $id_cadavre_exquis;
        public string $title;
        public string $date_start;
        public string $date_end;
        public int $nb_contribution;
        public int $nb_like;
        public int $id_admin;

        public int $contributions;

        public Periode $periode;

        public function __construct()
        {
            $this->periode = new Periode($this->date_start, $this->date_end);
        }
    }
?>