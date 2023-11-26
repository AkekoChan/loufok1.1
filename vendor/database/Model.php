<?php

namespace App\Service\Database;

use App\Models;

class Model
{
    protected string $table;
    protected string $entity;

    private static array $instances;

    final public static function getTableName(): string|null
    {
        return get_class_vars(static::class)["table"] ?? null;
    }

    final public static function instance(): Models\UsersModel|Models\CadavreExquisModel|Models\ContributionModel|Models\RandContributionModel
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) self::$instances[$class] = new static;
        return self::$instances[$class];
    }

    public function find(int $id): mixed
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE id_{$this->table} = :id";
        $sth = DatabaseManager::query($sql, [':id' => $id]);
        if ($sth && $sth->rowCount()) {
            $sth->setFetchMode(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
            return $sth->fetch();
        }
        return null;
    }

    public function findBy(array $criterias): ?array
    {
        foreach ($criterias as $f => $v) {
            $fields[] = "$f = ?";
            $values[] = $v;
        }

        $fields_list = implode(' AND ', $fields);
        $sql = "SELECT * FROM `{$this->table}` WHERE $fields_list";

        $sth = DatabaseManager::query($sql, $values);
        if ($sth && $sth->rowCount()) {
            return $sth->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        }

        return null;
    }

    public function findAll(): ?array
    {
        $req = "SELECT * FROM `{$this->table}`";
        $res = DatabaseManager::query($req);
        if ($res) {
            return $res->fetchAll(DatabaseManager::getInstance()::FETCH_CLASS, $this->entity);
        }
    }

    public function delete(int $id): int
    {
        $sql = "DELETE FROM `{$this->table}` WHERE id_{$this->table} = :id";
        $sth = DatabaseManager::query($sql, [':id' => $id]);

        return $sth->rowCount() > 0;
    }

    public function update(int $id, array $datas): bool
    {
        $sql = 'UPDATE `' . $this->table . '` SET ';
        foreach (array_keys($datas) as $k) {
            $sql .= " {$k} = :{$k} ,";
        }
        $sql = substr($sql, 0, strlen($sql) - 1);
        $sql .= " WHERE id_{$this->table} =:id";

        foreach (array_keys($datas) as $k) {
            $attributes[':' . $k] = $datas[$k];
        }
        $attributes[':id'] = $id;
        $sth = DatabaseManager::query($sql, $attributes);

        return $sth->rowCount() > 0;
    }

    public function exists(int $id): bool
    {
        $sql = "SELECT COUNT(*) AS c FROM `{$this->table}` WHERE id_{$this->table} = :id";
        $sth = DatabaseManager::query($sql, [':id' => $id]);
        // je met pas de fetch en class parce que c'est pour un exist, la fonction return un boolean
        if ($sth) {
            return ($sth->fetch()['c'] > 0);
        }
        return false;
    }

    public function create(array $datas, bool $returnEntity = true): mixed
    {
        $sql = 'INSERT INTO `' . $this->table . '` ( ';
        foreach (array_keys($datas) as $k) {
            $sql .= " {$k} ,";
        }
        $sql = substr($sql, 0, strlen($sql) - 1) . ' ) VALUE (';
        foreach (array_keys($datas) as $k) {
            $sql .= " :{$k} ,";
        }
        $sql = substr($sql, 0, strlen($sql) - 1) . ' )';

        foreach (array_keys($datas) as $k) {
            $attributes[':' . $k] = $datas[$k];
        }

        $sth = DatabaseManager::query($sql, $attributes);

        if ($sth) {
            $id = DatabaseManager::getInstance()->lastInsertId();
            if ($returnEntity) {
                return $this->find($id);
            }
            return $id;
        }

        return null;
    }
}
