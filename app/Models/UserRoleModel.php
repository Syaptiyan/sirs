<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'user_id',
        'role_id',
    ];
    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'role_id' => 'required|is_natural_no_zero',
    ];
    protected $beforeInsert = ['generateUUID'];

    protected function generateUUID(array $data): array
    {
        $data['data']['uuid'] = bin2hex(random_bytes(16));
        return $data;
    }
}
