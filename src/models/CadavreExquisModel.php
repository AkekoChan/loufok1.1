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
            $req = "SELECT
                ce.*,
                COUNT(c.id_contribution) AS contributions
            FROM
                Cadavre_Exquis ce
            LEFT JOIN
                Contribution c ON ce.id_cadavre_exquis = c.id_cadavre_exquis
            WHERE
                CURRENT_DATE BETWEEN ce.date_start AND ce.date_end
            LIMIT 1;";

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