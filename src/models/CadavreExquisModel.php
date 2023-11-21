<?php

namespace App\Models;

use App\Models\Entities\CadavreExquisEntity;
use App\Service\Database\DatabaseManager;
use App\Service\Database\Model;

class CadavreExquisModel extends Model
{
    public string $table = "cadavre_exquis";
    public string $entity = CadavreExquisEntity::class;

    public string $admin_table = "admin";

    public function getAllCadavre () : array {
        $contribution_table = ContributionModel::getTableName();

        $sql = "SELECT
            ce.*,
            a.mail_admin AS admin_mail,
            COUNT(c.id_contribution) AS contributions
        FROM
            {$this->table} ce
        JOIN
            {$this->admin_table} a ON ce.id_admin = a.id_admin
        LEFT JOIN
            {$contribution_table} c ON ce.id_cadavre_exquis = c.id_cadavre_exquis
        WHERE
            ce.date_end > NOW() AND ce.nb_contribution > (SELECT COUNT(*) FROM {$contribution_table} WHERE id_cadavre_exquis = ce.id_cadavre_exquis)
        GROUP BY
            ce.id_cadavre_exquis;";

        $sth = DatabaseManager::query($sql);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        }

        return [];
    }

    public function selectAllContributors (int $cadavre_id) : array {
        $contribution_table = ContributionModel::getTableName();
        $user_table = UsersModel::getTableName();

        $sql = "SELECT DISTINCT
                u.*
            FROM
                {$user_table} u
            JOIN
                {$contribution_table} c ON u.id_user = c.id_user
            WHERE
                c.id_cadavre_exquis = :cadavre_id;";

        $sth = DatabaseManager::query($sql, [":cadavre_id" => $cadavre_id]);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        }

        return [];
    }

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
