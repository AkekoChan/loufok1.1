<?php

namespace App\Models;

use App\Models\Entities\RandContributionEntity;
use App\Service\Database\Model;

class RandContributionModel extends Model
{
    public string $table = "randcontribution";
    public string $entity = RandContributionEntity::class;

    // Cette fonction récupère la contribution aléatoire correspondant à l'utilisateur et au cadavre actuel ou la créer
    public function getRandomContribution(int $user_id, int $current_cadavre_id): ContributionEntity
    {
        $contribution_table = ContributionModel::getTableName();
        $contribution_entity = ContributionEntity::class;

        $sql = "INSERT INTO {$this->table} (id_user, id_cadavre_exquis, num_contribution)
            SELECT
                :user_id,
                :cadavre_exquis_id,
                (SELECT id_contribution FROM {$contribution_table} WHERE id_cadavre_exquis = :cadavre_exquis_id ORDER BY RAND() LIMIT 1)
            WHERE NOT EXISTS (
                SELECT 1 FROM {$this->table} WHERE id_user = :user_id AND id_cadavre_exquis = :cadavre_exquis_id
            );";

        $sth = DatabaseManager::query($sql, [
            ":user_id" => $user_id,
            ":cadavre_exquis_id" => $current_cadavre_id
        ]);

        $select = "SELECT
                c.*
            FROM
                {$this->table} rc
            JOIN
                {$contribution_table} c ON rc.num_contribution = c.id_contribution
            WHERE
                rc.id_user = :user_id
                AND rc.id_cadavre_exquis = :cadavre_exquis_id
            LIMIT 1;";

        $sth = DatabaseManager::query($select, [
            ":user_id" => $user_id,
            ":cadavre_exquis_id" => $current_cadavre_id
        ]);

        if (!$sth || $sth->rowCount() === 0) return false; // TODO HANDLE ERROR

        $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $contribution_entity);
        return $sth->fetch();
    }
}
