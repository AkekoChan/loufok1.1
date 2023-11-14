<?php
    namespace App\Helpers;

    class Periode {
        public string $start;
        public string $end;

        public function __construct(string $date_start, string $date_end)
        {
            $this->start = $date_start;
            $this->end = $date_end;
        }
    }
?>