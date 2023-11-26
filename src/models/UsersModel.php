<?php

namespace App\Models;

use App\Service\Database\DatabaseManager;
use App\Service\Database\Model;

use App\Entities;

class UsersModel extends Model
{
    public string $table = "user";
    public string $entity = Entities\UserEntity::class;

    private string $admin_table = "admin";

    public function getUser(string $email): Entities\UserEntity|null
    {
        $sql = "SELECT 0 AS is_admin, id_user AS id, nom, mail, sexe, bdate, password
            FROM {$this->table}
            WHERE mail = ?
            UNION
            SELECT 1 AS is_admin, id_admin AS id, NULL AS nom, mail_admin AS mail, NULL AS sexe, NULL AS bdate, password
            FROM {$this->tableAdmin}
            WHERE mail_admin = ?;";

        $sth = DatabaseManager::query($sql, [$email, $email]);
        if ($sth && $sth->rowCount()) {
            $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            return $sth->fetch();
        }

        return false;
    }

    public function getUserById(int $id, bool $is_admin = false): Entities\UserEntity
    {
        if ($is_admin) {
            $sql = "SELECT 1 AS is_admin, id_admin AS id, mail_admin as mail
                FROM {$this->admin_table}
                WHERE id_admin = :id;";
        } else {
            $sql = "SELECT 0 AS is_admin, id_user AS id, nom, mail, sexe, bdate
                FROM {$this->table}
                WHERE id_user = :id";
        }

        $sth = DatabaseManager::query($sql, [":id" => $id]);

        $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        return $sth->fetch();
    }

    // public function getContributionFromCadavre (int $user_id, int $cadavre_id, bool $is_admin = false) : ContributionEntity|null {
    //     $idfield = $is_admin ? "id_admin" : "id_user";
    //     return ContributionModel::instance()->findBy([
    //         $idfield => $user_id,
    //         "id_cadavre_exquis" => $cadavre_id
    //     ])[0] ?? null;
    // }

    // public function getAllCadavres (int $user_id, bool $is_admin = false) : array {
    //     $contribution_table = ContributionModel::getTableName();
    //     $cadavre_table = CadavreExquisModel::getTableName();

    //     $idfield = $is_admin ? "c.id_admin" : "c.id_user";
    //     $sql = "SELECT DISTINCT
    //         ce.*
    //     FROM
    //         {$cadavre_table} ce
    //     JOIN
    //         {$contribution_table} c ON ce.id_cadavre_exquis = c.id_cadavre_exquis
    //     WHERE
    //         (ce.date_end < NOW() OR ce.nb_contribution <= (SELECT COUNT(*) FROM {$contribution_table} WHERE id_cadavre_exquis = ce.id_cadavre_exquis))
    //         AND $idfield = :user_id;";

    //     $sth = DatabaseManager::query($sql, [":user_id" => $user_id]);
    //     if ($sth && $sth->rowCount()) {
    //         return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, CadavreExquisEntity::class);
    //     }

    //     return [];
    // }

    // public function getAllContributions (int $user_id, bool $is_admin = false) : array {
    //     $contribution_table = ContributionModel::getTableName();
    //     $cadavre_table = CadavreExquisModel::getTableName();

    //     if($is_admin) {
    //         $sql = "SELECT
    //         c.*,
    //         ce.title AS cadavre_name
    //         FROM
    //             {$contribution_table} c
    //         JOIN
    //             {$cadavre_table} ce ON c.id_cadavre_exquis = ce.id_cadavre_exquis
    //         WHERE
    //             c.id_user IS NULL AND c.id_admin = :id";
    //     } else {
    //         $sql = "SELECT
    //         c.*,
    //         ce.title AS cadavre_name
    //         FROM
    //             {$contribution_table} c
    //         JOIN
    //             {$cadavre_table} ce ON c.id_cadavre_exquis = ce.id_cadavre_exquis
    //         WHERE
    //         c.id_user = :id;";
    //     }

    //     $sth = DatabaseManager::query($sql, [":id" => $user_id]);
    //     if ($sth && $sth->rowCount()) {
    //         return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, ContributionEntity::class);
    //     }

    //     return [];
    // }
}
