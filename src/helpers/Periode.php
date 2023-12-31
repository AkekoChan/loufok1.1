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

        public function getRemainingDays () : int {
            $now = date_create("now");
            $end = date_create($this->end . "23:59:59");
            return max(1, date_diff($now, $end, true)->days);
        }

        public function getDiff () : int {
            $start = date_create($this->start . "00:00:01");
            $end = date_create($this->end . "23:59:59");
            return max(1, date_diff($start, $end, true)->days);
        }

        public function isActive () : bool {
            $now = date_create("now");
            $start = date_create($this->start . "00:00:01");
            $end = date_create($this->end . "23:59:59");
            return $now > $start && $now < $end;
        }
        
        public function getConvertedPeriode () : array {
            return [
                "start" => preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $this->start),
                "end" => preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $this->end)
            ];
        }
    }
?>