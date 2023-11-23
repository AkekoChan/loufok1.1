<?php

namespace App\Models;

use App\Service\Database\DatabaseManager;
use App\Service\Database\Model;

use App\Entities;

class CadavreExquisModel extends Model
{
    public string $table = "cadavre_exquis";
    public string $entity = Entities\CadavreExquisEntity::class;

    public function getCurrentCadavre(): Entities\CadavreExquisEntity|null
    {
        $contribution_table = ContributionModel::getTableName();
        $sql = "SELECT * FROM {$this->table} ce WHERE
                (CURRENT_DATE BETWEEN ce.date_start AND ce.date_end)
                AND ce.max_contributions > (SELECT COUNT(*) FROM {$contribution_table} WHERE id_cadavre_exquis = ce.id_cadavre_exquis)
            GROUP BY id_cadavre_exquis
            LIMIT 1;";

        $sth = DatabaseManager::query($sql);

        if ($sth && $sth->rowCount()) {
            // $res->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            return $sth->fetch();
        }

        return null;
    }

    public function getUserLastCadavre (int $user_id, bool $is_admin = false) : Entities\CadavreExquisEntity|null {
        $contribution_table = ContributionModel::getTableName();
        $admin_clause = $is_admin ? "c.id_admin = :user_id AND c.id_user IS NULL" : "c.id_user = :user_id";

        $sql = "SELECT ce.* FROM {$this->table} ce
        JOIN
            {$contribution_table} c ON ce.id_cadavre_exquis = c.id_cadavre_exquis
        WHERE
            {$admin_clause}
        GROUP BY
            ce.id_cadavre_exquis
        HAVING
            ce.date_end < NOW() OR COUNT(c.id_contribution) >= ce.max_contributions
        ORDER BY
            ce.date_end DESC
        LIMIT 1;";

        $sth = DatabaseManager::query($sql, [ "user_id" => $user_id ]);
        if ($sth && $sth->rowCount()) {
            $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            return $sth->fetch();
        }

        return null;
    }

    public function getAllCadavresNotFinished () : array {
        $contribution_table = ContributionModel::getTableName();
        $sql = "SELECT * FROM {$this->table} ce
        WHERE
            ce.date_end >= NOW() AND ce.max_contributions > (SELECT COUNT(*) FROM {$contribution_table} WHERE id_cadavre_exquis = ce.id_cadavre_exquis)
        GROUP BY
            ce.id_cadavre_exquis
        ORDER BY
            ce.date_start;";

        $sth = DatabaseManager::query($sql);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        }

        return [];
    }

    public function getCadavreTitle (int $cadavre_id) : string|null {
        $sql = "SELECT title FROM {$this->table} WHERE id_cadavre_exquis = :cadavre_id";

        $sth = DatabaseManager::query($sql, [ ":cadavre_id" => $cadavre_id ]);
        return $sth ? $sth->fetch()["title"] : null;
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
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, Entities\UserEntity::class);
        }

        return [];
    }

    public function new (string $title, string $ds, string $de, string $text, int $max, int $id) : bool {
        $sql = "INSERT INTO {$this->table} (title, date_start, date_end, max_contributions, id_admin)
        VALUES (:title, :ds, :de, :max, :id)";
    
        $sth = DatabaseManager::query($sql, [
            ":title" => htmlspecialchars(strip_tags($title)),
            ":title" => htmlspecialchars(trim(strip_tags($title))),
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
            ":text" => htmlspecialchars(strip_tags($text)),
            ":text" => htmlspecialchars(trim(strip_tags($text))),
            ":id_cadavre" => $id_cadavre,
            ":id" => $id
        ]);

        return $sthc ? true : false;
    }

    public function periodeOverlap (string $date_start, string $date_end) : bool {
        $sql = "SELECT DISTINCT ce.id_cadavre_exquis FROM {$this->table} ce
        WHERE NOT (ce.date_start > :date_end OR ce.date_end < :date_start);";
            
        $sth = DatabaseManager::query($sql, [":date_start" => $date_start, ":date_end" => $date_end]);
        return ($sth && $sth->rowCount());
    }
}
