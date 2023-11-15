<?php
    namespace App\Models;

    use App\Models\Entities\CadavreExquisEntity;
    use App\Service\Database\DatabaseManager;
    use App\Service\Database\Model;

    class CadavreExquisModel extends Model {
        public string $table = "cadavre_exquis";
        public string $entity = CadavreExquisEntity::class;

        public function getAllPeriodes () : array {
            // TODO
            return [];
        }

        public function getCurrentCadavre () : CadavreExquisEntity|bool {
            $req = "SELECT *
            FROM `{$this->table}`
            WHERE date_start <= DATE(NOW()) AND date_end > DATE(NOW()) LIMIT 1;";

            $sth = DatabaseManager::query($req);
            if ($sth && $sth->rowCount()) {
                // $res->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
                $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
                return $sth->fetch();
            }

            return false;
        }
    }
?>