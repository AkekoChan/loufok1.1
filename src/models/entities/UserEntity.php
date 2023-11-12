<?php
    namespace App\Models\Entities;

    class UserEntity {
        public bool $is_admin;
        public int $id;
        public string $password;
        public string $mail;
        public string|null $nom;
        public string|null $sexe;
        public string|null $bdate;
    }
?>