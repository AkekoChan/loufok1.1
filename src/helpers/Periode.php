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

        public function getConvertedPeriode () : array {
            return [
                preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $this->start),
                preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $this->end)
            ];
        }
    }
?>