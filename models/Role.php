<?php

/**
 * Role Model
 * Tương đương Role. java + RoleRepository.java
 * 
 * Table: roles
 * Relationships: Role (1) -> (N) User
 */

require_once __DIR__ . '/../core/Model.php';

class Role extends Model
{
    protected $table = 'roles';

    /**
     * Tương đương findByName() trong RoleRepository
     */
    public function findByName($name)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = :name LIMIT 1";
        return $this->queryOne($sql, ['name' => $name]);
    }

    /**
     * Lấy tất cả users thuộc role này
     * Tương đương role.getUsers() trong Java
     */
    public function getUsers($roleId)
    {
        $sql = "SELECT * FROM users WHERE role_id = :role_id";
        return $this->query($sql, ['role_id' => $roleId]);
    }
}
