<?php

namespace App\Models;

use App\Models\Entities\CadavreExquisEntity;
use App\Models\Entities\ContributionEntity;
use App\Models\Entities\UserEntity;
use App\Service\Database\DatabaseManager;
use App\Service\Database\Model;

class CadavreExquisModel extends Model
{
    public string $table = "cadavre_exquis";
    public string $entity = CadavreExquisEntity::class;

    public string $admin_table = "admin";

    public function createCadavre (string $title, string $ds, string $de,
        string $text, int $max, int $id) : bool {
            $sql = "INSERT INTO {$this->table} (title, date_start, date_end, nb_contribution, id_admin)
                VALUES (:title, :ds, :de, :max, :id)";
            
        $sth = DatabaseManager::query($sql, [
            ":title" => $title,
            ":ds" => $ds,
            ":de" => $de,
            ":max" => $max,
            ":id" => $id
        ]);

        if (!$sth) return false;

        $id_cadavre = DatabaseManager::getInstance()->lastInsertId();
        $contribution_table = ContributionModel::getTableName();

        $sqlc = "INSERT INTO {$contribution_table} (text, id_cadavre_exquis, id_admin)
            VALUES (:text, :id_cadavre, :id)";

        $sthc = DatabaseManager::query($sqlc, [
            ":text" => $text,
            ":id_cadavre" => $id_cadavre,
            ":id" => $id
        ]);

        return $sthc ? true : false;
    }

    public function getAllCadavresNotFinished () : array {
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

    public function getAllCadavres () : array {
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
            ce.date_end > NOW() AND ce.date_start <= NOW() AND ce.nb_contribution > (SELECT COUNT(*) FROM {$contribution_table} WHERE id_cadavre_exquis = ce.id_cadavre_exquis)
        GROUP BY
            ce.id_cadavre_exquis;";

        $sth = DatabaseManager::query($sql);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        }

        return [];
    }

    public function getAllContributions (int $cadavre_id) : array {
        $contribution_table = ContributionModel::getTableName();
        $sql = "(SELECT *
            FROM {$contribution_table} c
            WHERE c.id_cadavre_exquis = :cadavre_id
          ) 
          ORDER BY submission_order";

        $sth = DatabaseManager::query($sql, [":cadavre_id" => $cadavre_id]);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, ContributionEntity::class);
        }
        return [];
    }

    public function getAllContributors (int $cadavre_id) : array {
        $contribution_table = ContributionModel::getTableName();
        $user_table = UsersModel::getTableName();

        $sql = "SELECT DISTINCT
            0 AS is_admin,
            u.id_user AS id,
            u.nom
        FROM
            {$user_table} u
        JOIN
            {$contribution_table} c ON u.id_user = c.id_user
        WHERE
            c.id_cadavre_exquis = :cadavre_id;";

        $sth = DatabaseManager::query($sql, [":cadavre_id" => $cadavre_id]);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, UserEntity::class);
        }

        return [];
    }

    public function cadavrePeriodeOverlap (string $date_start, string $date_end) : bool {
        $sql = "SELECT DISTINCT
        ce.id_cadavre_exquis
        FROM
            {$this->table} ce
        WHERE
            NOT (
                ce.date_start > :date_end OR
                ce.date_end < :date_start
            );";
            
        $sth = DatabaseManager::query($sql, [":date_start" => $date_start, ":date_end" => $date_end]);
        if ($sth && $sth->rowCount()) {
            return true;
        }
        return false;
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
