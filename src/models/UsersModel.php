<?php
    namespace App\Models;

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
    }
?>