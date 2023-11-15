<?php

namespace App\Models;

use App\Models\Entities\CadavreExquisEntity;
use App\Service\Database\DatabaseManager;
use App\Service\Database\Model;

class CadavreExquisModel extends Model
{
    public string $table = "cadavre_exquis";
    public string $entity = CadavreExquisEntity::class;

    public function getCurrentCadavre(): CadavreExquisEntity|null
    {
        $contribution_table = ContributionModel::getTableName();

        $sql = "SELECT
                ce.*,
                COUNT(c.id_contribution) AS contributions
            FROM
                {$this->table} ce
            LEFT JOIN
                {$contribution_table} c ON ce.id_cadavre_exquis = c.id_cadavre_exquis
            WHERE
                (CURRENT_DATE BETWEEN ce.date_start AND ce.date_end)
                AND ce.nb_contribution > (SELECT COUNT(*) FROM {$contribution_table} WHERE id_cadavre_exquis = ce.id_cadavre_exquis)
            GROUP BY ce.id_cadavre_exquis
            LIMIT 1;";

        $sth = DatabaseManager::query($sql);
        if ($sth && $sth->rowCount()) {
            // $res->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            return $sth->fetch();
        }

        return null;
    }
}
