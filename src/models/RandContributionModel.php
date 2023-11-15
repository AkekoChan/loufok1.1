<?php
    namespace App\Models;

    use App\Service\Database\Model;
    use App\Service\Database\DatabaseManager;

    use App\Models\Entities\RandContributionEntity;

    class RandContributionModel extends Model {
        public string $table = "randcontribution";
        public string $entity = RandContributionEntity::class;

        // Cette fonction récupère la contribution aléatoire correspondant à l'utilisateur et au cadavre actuel ou la créer
        public function getRandomContribution (int $user_id, int $current_cadavre_id) : RandContributionEntity {
            $sql = "SELECT *
            FROM {$this->table}
            WHERE id_user = :user_id AND id_cadavre_exquis = :cadavre_id
            LIMIT 1;";

            $sth = DatabaseManager::query($sql, [
                ":user_id" => $user_id,
                ":cadavre_id" => $current_cadavre_id
            ]);

            if ($sth && $sth->rowCount()) {
                // $res->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
                $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
                return $sth->fetch();
            } else {
                return $this->assignRandomContribution($user_id, $current_cadavre_id);
            }
        }

        // Cette fonction assigne une contribution aléatoire à l'utilisateur en fonction du cadavre actuel
        private function assignRandomContribution (int $user_id, int $current_cadavre_id) : RandContributionEntity {
            $contribution_table = ContributionModel::getTableName();

            $sql = "INSERT INTO {$this->table} (id_user, id_cadavre_exquis, num_contribution)
                        VALUES (
                            :user_id,
                            :cadavre_id,
                            (SELECT id_contribution FROM {$contribution_table} WHERE id_cadavre_exquis = :cadavre_id ORDER BY RAND() LIMIT 1)
                        );";
                
            $sth = DatabaseManager::query($sql, [
                    ":user_id" => $user_id,
                    ":cadavre_id" => $current_cadavre_id
            ]);

            $select = "SELECT *
            FROM {$this->table}
            WHERE id_user = :user_id AND id_cadavre_exquis = :cadavre_id
            LIMIT 1;";

            $sth = DatabaseManager::query($select, [
                ":user_id" => $user_id,
                ":cadavre_id" => $current_cadavre_id
            ]);

            $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            return $sth->fetch();
        }
    }
?>