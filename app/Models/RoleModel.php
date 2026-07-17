<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'uuid',
        'name',
        'slug',
        'description',
        'is_active',
    ];
    protected $validationRules = [
        'name'  => 'required|min_length[3]|max_length[50]|is_unique[roles.name,id,{id}]',
        'slug'  => 'required|max_length[50]|is_unique[roles.slug,id,{id}]',
    ];
    protected $beforeInsert = ['generateUUID'];

    protected function generateUUID(array $data): array
    {
        $data['data']['uuid'] = bin2hex(random_bytes(16));
        return $data;
    }
}
