<?php
    namespace App\Models;

    use App\Models\Entities\CadavreExquisEntity;
    use App\Models\Entities\ContributionEntity;
    use App\Models\Entities\UserEntity;
    use App\Service\Database\DatabaseManager;
    use App\Service\Database\Model;

    class UsersModel extends Model {
        public string $table = "user";
        public string $entity = UserEntity::class;

        public string $admin_table = "admin";

        public function retrieveUser (string $email) : UserEntity|null {
            $sql = "SELECT 0 AS is_admin, id_user AS id, nom, mail, sexe, bdate, password
            FROM {$this->table}
            WHERE mail = :email
            UNION
            SELECT 1 AS is_admin, id_admin AS id, NULL AS nom, mail_admin AS mail, NULL AS sexe, NULL AS bdate, password
            FROM {$this->admin_table}
            WHERE mail_admin = :email;";

            $sth = DatabaseManager::query($sql, [":email" => $email]);
            if ($sth && $sth->rowCount()) {
                $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
                return $sth->fetch();
            }

            return null;
        }

        public function getContributionFromCadavre (int $user_id, int $cadavre_id) : ContributionEntity|null {
            return ContributionModel::instance()->findBy([
                "id_user" => $user_id,
                "id_cadavre_exquis" => $cadavre_id
            ]);
        }

        public function getAllCadavres (int $user_id) : array {
            $contribution_table = ContributionModel::getTableName();
            $cadavre_table = CadavreExquisModel::getTableName();

            $sql = "SELECT DISTINCT
                ce.*
            FROM
                {$cadavre_table} ce
            JOIN
                {$contribution_table} c ON ce.id_cadavre_exquis = c.id_cadavre_exquis
            WHERE
                c.id_user = :user_id;";

            $sth = DatabaseManager::query($sql, [":user_id" => $user_id]);
            if ($sth && $sth->rowCount()) {
                return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, CadavreExquisEntity::class);
            }
    
            return [];
        }

        public function getAllContributions (int $user_id) : array {
            $contribution_table = ContributionModel::getTableName();
            $cadavre_table = CadavreExquisModel::getTableName();

            $sql = "SELECT
                c.*,
                ce.title AS cadavre_name
            FROM
                {$contribution_table} c
            JOIN
                {$cadavre_table} ce ON c.id_cadavre_exquis = ce.id_cadavre_exquis
            WHERE
                c.id_user = :id;";
    
            $sth = DatabaseManager::query($sql, [":id" => $user_id]);
            if ($sth && $sth->rowCount()) {
                return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, ContributionEntity::class);
            }
    
            return [];
        }
    }
?>