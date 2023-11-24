<?php
    namespace App\Models;

    use App\Service\Database\DatabaseManager;
    use App\Service\Database\Model;

    use App\Entities;

    class UsersModel extends Model {
        public string $table = "user";
        public string $entity = Entities\UserEntity::class;

        private string $admin_table = "admin";

        public function getUser (string $email) : Entities\UserEntity|null {
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

        public function getUserById (int $id, bool $is_admin = false) : Entities\UserEntity {
            if($is_admin) {
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
    }
?>